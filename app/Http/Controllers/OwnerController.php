<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Order_Product;
use App\Models\Product;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function profil()
    {
        return view('owner.profil');
    }

    public function store(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);
        return redirect()->route('owner.profil')
            ->with('success', 'Password berhasil diubah');
    }

    public function index0()
    {
        $products = Product::all();
        $orders = Order::all();
        $orders2 = Order::orderBy('date', 'DESC')
            ->limit(5)
            ->get();
        $users1 = User::where('role', '=', 'customer');
        $users2 = User::where('role', '=', 'admin');
        $terlaris = Order_Product::join('products', 'order_product.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('count(order_product.quantity) as total'))
            ->groupBy('products.name')
            ->orderBy('total', 'DESC')
            ->take(5)
            ->get();

        $pemesan = Order_Product::join('orders', 'order_product.order_id', '=', 'orders.id')
            ->select(
                'orders.receiver',
                DB::raw('count(order_product.quantity) as total'),
                DB::raw('SUM(order_product.subtotal) as subtotal')
            )
            ->groupBy('orders.receiver')
            ->orderBy('total', 'DESC')
            ->take(5)
            ->get();

        $visitor = Order::select(
            DB::raw("DATE_FORMAT(date, '%M') as month"),
            DB::raw('SUM(total_price) as total')
        )
            ->groupBy(DB::raw("DATE_FORMAT(date, '%M')"))
            ->orderBy(DB::raw("MIN(date)"))
            ->get();


        $data = [];
        foreach ($visitor as $row) {
            $data['label'][] = $row->month;
            $data['data'][] = (int) $row->total;
        }
        $data['chart_data'] = json_encode($data);
        return view(
            'owner.index',
            $data,
            compact('products', 'orders', 'users1', 'users2', 'terlaris', 'orders2', 'pemesan')
        );
    }

    public function penjualan(Request $request)
    {
        $startDate = $request->input('from_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('to_date', Carbon::now()->endOfMonth()->toDateString());

        // Data order detail
        $orders = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->select(
                'orders.id as order_id',
                'orders.date',
                'orders.status',
                'orders.created_at',
                DB::raw('GROUP_CONCAT(products.name) as product_names'),
                DB::raw('SUM(order_product.subtotal) as total_subtotal')
            )
            ->whereBetween('orders.date', [$startDate, $endDate])
            ->groupBy('orders.id', 'orders.date', 'orders.status', 'orders.created_at')
            ->get();

        // Penjualan Harian (group by date)
        $dailySales = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->whereBetween('orders.date', [$startDate, $endDate])
            ->groupBy('orders.date')
            ->orderBy('orders.date')
            ->select('orders.date', DB::raw('SUM(order_product.subtotal) as total_sales'))
            ->get();

        // Penjualan Bulanan (group by month)
        $monthlySales = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->whereBetween('orders.date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE_FORMAT(orders.date, "%Y-%m")')) // 👈 perubahan di sini
            ->orderBy(DB::raw('DATE_FORMAT(orders.date, "%Y-%m")'))
            ->select(
                DB::raw('DATE_FORMAT(orders.date, "%Y-%m") as month'),
                DB::raw('SUM(order_product.subtotal) as total_sales')
            )
            ->get();

        // Penjualan per Kategori
        $salesByCategory = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->whereBetween('orders.date', [$startDate, $endDate])
            ->groupBy('products.category_id')  // asumsikan produk punya kolom category
            ->select('products.category_id', DB::raw('SUM(order_product.subtotal) as total_sales'))
            ->get();

        // Produk Terlaris
        $topProducts = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->whereBetween('orders.date', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name')
            ->select(
                'products.name',
                DB::raw('SUM(order_product.quantity) as total_quantity'),
                DB::raw('SUM(order_product.subtotal) as total_revenue')
            )
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $statusDistribution = Order::byDateRange($startDate, $endDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $stats = [
            'total_sales' => $orders->sum('total_subtotal'),
            'order_count' => $orders->count(),
            'avg_order_value' => $orders->avg('total_subtotal'),
            'status_distribution' => $statusDistribution,
            'daily_sales' => $dailySales,
            'monthly_sales' => $monthlySales,
            'sales_by_category' => $salesByCategory,
            'top_products' => $topProducts,
        ];

        return view('owner.laporan_penjualan', compact('orders', 'startDate', 'endDate', 'stats'));
    }

    public function index2()
    {
        $orders = Order::orderBy('id', 'desc')->get();
        return view('owner.laporan_pesanan', compact('orders'));
    }

    public function index3()
    {
        $products = Product::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('owner.data_produk', compact('products', 'categories'));
    }

    public function pesananLaporanDetail($id)
    {
        // Ambil data pesanan berdasarkan ID
        $order = Order::findOrFail($id);

        // Ambil semua produk dalam pesanan
        $details = Order_Product::where('order_id', $id)->get();
        $identity = Order_Product::where('order_id', $id)->first();

        // Hitung subtotal untuk setiap produk
        foreach ($details as $detail) {
            $detail->subtotal = $detail->quantity * $detail->price;
        }

        // Kirim data ke view admin
        return view('owner.laporan_detail', compact('details', 'identity', 'id'));
    }

    public function index4()
    {
        $user = User::where('role', '=', 'customer')
            ->orderBy('name', 'asc')
            ->get();
        return view('owner.data_pelanggan', compact('user'));
    }

    public function index5()
    {
        $user = User::where('role', '=', 'admin')
            ->orderBy('name', 'asc')
            ->get();
        return view('owner.data_admin', compact('user'));
    }

    public function laporan_penjualan(Request $request)
    {
        $startDate = $request->input('from_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('to_date', now()->toDateString());

        if ($startDate > $endDate) {
            return redirect()->back()->withErrors(['date' => 'Tanggal awal tidak boleh lebih besar dari tanggal akhir']);
        }

        // Data yang sudah ada
        $totalSales = Order::byDateRange($startDate, $endDate)->byStatus('dibayar')->sum('total_price');
        $orderCount = Order::byDateRange($startDate, $endDate)->byStatus('dibayar')->count();
        $avgOrderValue = $orderCount ? $totalSales / $orderCount : 0;
        $dailySales = Order::byDateRange($startDate, $endDate)
            ->byStatus('dibayar')
            ->selectRaw('DATE(date) as sale_date, SUM(total_price) as total')
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->pluck('total', 'sale_date')
            ->toArray();
        $topProducts = Order_Product::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->byDateRange($startDate, $endDate)->byStatus('dibayar');
        })
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(order_product.quantity) as total_quantity, SUM(order_product.subtotal) as total_revenue')
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
        $statusDistribution = Order::byDateRange($startDate, $endDate)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Tambahan: Penjualan per Kategori
        $salesByCategory = Order_Product::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->byDateRange($startDate, $endDate)->byStatus('dibayar');
        })
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, SUM(order_product.subtotal) as total')
            ->groupBy('categories.name')
            ->pluck('total', 'category')
            ->toArray();

        // Tambahan: Tren Bulanan
        $monthlySales = Order::byDateRange($startDate, $endDate)
            ->byStatus('dibayar')
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(total_price) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Tambahan: Perbandingan Periode Sebelumnya
        $prevStartDate = Carbon::parse($startDate)->subDays(Carbon::parse($endDate)->diffInDays($startDate) + 1)->toDateString();
        $prevEndDate = Carbon::parse($startDate)->subDay()->toDateString();
        $prevTotalSales = Order::byDateRange($prevStartDate, $prevEndDate)->byStatus('dibayar')->sum('total_price');
        $salesGrowth = $prevTotalSales ? (($totalSales - $prevTotalSales) / $prevTotalSales * 100) : 0;

        $stats = [
            'total_sales' => $totalSales,
            'order_count' => $orderCount,
            'avg_order_value' => $avgOrderValue,
            'daily_sales' => $dailySales,
            'top_products' => $topProducts,
            'status_distribution' => $statusDistribution,
            'sales_by_category' => $salesByCategory,
            'monthly_sales' => $monthlySales,
            'sales_growth' => $salesGrowth,
        ];

        return view('owner.cetak_laporan_penjualan', compact('stats', 'startDate', 'endDate'));
    }
    public function pesananLaporan(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->from_date)) {
                $data = DB::table('orders')
                    ->whereBetween('date', array($request->from_date, $request->to_date))
                    ->get();
            } else {
                $data = DB::table('orders')
                    ->get();
            }
            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    $detail = '<a href="' . route('pesanan.data.detail', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa-solid fa-circle-info"></i></a>';
                    return $detail;
                })
                ->addIndexColumn()
                ->editColumn('status', function ($data) {

                    if ($data->status == 'belum bayar') {
                        // return '<img src=" '.url($data->status).' "/>';
                        return '<button type="button" class="btn bg-maroon">' . $data->status . '</button>';
                    } elseif ($data->status == 'menunggu verifikasi') {
                        return '<button type="button" class="btn bg-orange">' . $data->status . '</button>';
                    } elseif ($data->status == 'dibayar') {
                        return '<button type="button" class="btn btn-success">' . $data->status . '</button>';
                    } else {
                        return '<button type="button" class="btn bg-danger">' . $data->status . '</button>';
                    }
                })
                ->editColumn('total_price', function ($data) {
                    return 'Rp. ' . number_format($data->total_price, 0) . ' ';
                })
                ->rawColumns(['status', 'action', 'total_price', 'number'])->make(true);
        }
    }
    public function produkOwner()
    {
        $data = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.name as pname',
                'categories.name as cname',
                'products.description',
                'products.price',
                'products.stoks',
                'products.image'
            );
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('image', function ($data) {
                return '<img src=" ' . url($data->image) . ' "/>';
            })
            ->editColumn('stoks', function ($data) {
                if ($data->stoks == 0) {
                    $actiona = '<a href="#' . route('change.stoks', $data->id) . '" class="btn btn-xs btn-danger" >Habis</a>';
                } else {
                    $actiona = '<a href="#' . route('change.stoks', $data->id) . '" class="btn btn-xs btn-primary" >Ada</a>';
                }
                return $actiona;
            })
            ->rawColumns(['image', 'stoks'])
            ->make(true);
    }

    public function pelangganOwner()
    {
        $data = User::where('role', '=', 'customer')
            ->select('id', 'name', 'email', DB::raw("DATE_FORMAT(created_at, '%d-%b-%Y') as month"))
            ->orderBy('name', 'asc')
            ->get();
        return Datatables::of($data)->make(true);
    }

    public function adminOwner()
    {
        $data = User::where('role', '=', 'admin')
            ->select('id', 'name', 'email', DB::raw("DATE_FORMAT(created_at, '%d-%b-%Y') as month"))
            ->orderBy('name', 'asc')
            ->get();
        return Datatables::of($data)->make(true);
    }

    public function storeAdmin(Request $r)
    {
        // validasi input
        $r->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        // simpan user dengan role admin
        User::create([
            'name'     => $r->name,
            'email'    => $r->email,
            'password' => Hash::make($r->password),
            'role'     => 'admin',
        ]);
        // redirect kembali dengan notifikasi   
        return back()->with('success', 'Admin baru berhasil dibuat');
    }

    public function penjualan_cetak()
    {
        $category = Category::get();
        return view('owner.cetak_laporan_penjualan', compact('category'));
    }

    public function pesanan_cetak()
    {
        // dd('tes');
        return view('owner.cetak_laporan_pesanan');
    }

    public function cari(Request $request)
    {
        $produk = Category::all();
        $start_date = Carbon::parse($request->start_date)->toDateTimeString();
        $end_date = Carbon::parse($request->end_date)->toDateTimeString();
        $category = $request->category;
        $name = $request->name;
        $orders = Order_Product::join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->select(
                'order_product.id',
                'products.name',
                'products.price',
                'products.category_id',
                'order_product.quantity',
                'order_product.subtotal',
                'orders.date',
                'orders.status'
            )
            ->where('orders.status', '=', 'dibayar');
        if ($category != '---Pilih Kategori---') {
            $orders = $orders->where('products.category_id', '=', $category);
            $sum = $orders->where('products.category_id', '=', $category)
                ->sum('order_product.subtotal');
            $sum2 = $orders->where('products.category_id', '=', $category)
                ->sum('products.price');
            $sum3 = $orders->where('products.category_id', '=', $category)
                ->sum('order_product.quantity');
        }
        if ($name != '---Pilih Nama---') {
            $orders = $orders->where('products.id', '=', $name);
            $sum = $orders->where('products.category_id', '=', $category)
                ->sum('order_product.subtotal');
            $sum2 = $orders->where('products.category_id', '=', $category)
                ->sum('products.price');
            $sum3 = $orders->where('products.category_id', '=', $category)
                ->sum('order_product.quantity');
        }
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $orders = $orders->whereBetween('orders.date', [$start_date, $end_date]);
            $sum = $orders->sum('order_product.subtotal');
            $sum2 = $orders->sum('products.price');
            $sum3 = $orders->sum('order_product.quantity');
        }
        $orders = $orders->get();
        return view('owner.new_laporan_tercetak', compact(
            'orders',
            'produk',
            'sum',
            'sum2',
            'sum3',
            'start_date',
            'end_date'
        ));
    }

    public function kategori(Request $request)
    {
        $category = Product::where("category_id", $request->category_id)->pluck('id', 'name');
        return response()->json($category);
    }

    public function cari2(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->toDateTimeString();
        $end_date = Carbon::parse($request->end_date)->toDateTimeString();
        $name = $request->name;
        $orders = Order_Product::join('orders', 'order_product.order_id', '=', 'orders.id')
            ->select(
                'orders.id',
                'orders.user_id',
                'orders.receiver',
                'orders.address',
                'orders.total_price',
                'orders.date',
                'order_product.quantity'
            )
            ->where('status', '=', 'dibayar');
        if (!empty($name)) {
            $orders = $orders->where('receiver', '=', $name);
            $sum = $orders->where('receiver', '=', $name)
                ->sum('total_price');
        }
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $orders = $orders->whereBetween('orders.date', [$start_date, $end_date]);
            $sum = $orders->sum('total_price');
        }
        $orders = $orders->get();
        return view(
            'owner.new_laporan_tercetak_pemesanan',
            compact('orders', 'start_date', 'end_date')
        );
    }
}
