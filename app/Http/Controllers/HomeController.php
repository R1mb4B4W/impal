<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\order;
use App\Models\Category; // Menambahkan import model Category


class HomeController extends Controller
{
    public function welcome()
    {
        $products = Product::where('stoks', 1)
            ->where('category_id', 3) // Misalnya kategori promo
            ->paginate(12); // Mengganti get() dengan paginate(12)
        return view('front.home', compact('products'));
    }

    public function index()
    {
        $products = Product::where('stoks', 1)
            ->where('category_id', 3)
            ->paginate(12); // Mengganti get() dengan paginate(12)
        return view('front.home', compact('products'));
    }
    
    public function makanan()
    {
        $products = Product::where('stoks', 1)
            ->where('category_id', 1) // Kategori Makanan
            ->get();
        return view('front.menu', compact('products'));
    }

    public function minuman()
    {
        $products = Product::where('stoks', 1)
            ->where('category_id', 2) // Kategori Minuman
            ->get();
        return view('front.menu', compact('products'));
    }

    public function promo()
    {
        $products = Product::where('stoks', 1)
            ->where('category_id', 3) // Kategori Promo
            ->get();
        return view('front.menu', compact('products'));
    }


    public function semua()
    {
        $products = Product::where('stoks', 1)->get();
        return view('front.menu', compact('products'));
    }

    public function all()
    {
        $products = Product::where('stoks', 1)
            ->orderBy('category_id', 'ASC')
            ->get();
        return view('front.menu', compact('products'));
    }
    public function cari(Request $request)
    {
        // Ambil kata kunci pencarian dari input
        $cari = $request->cari;

        // Pencarian produk berdasarkan nama yang mengandung kata kunci
        $products = DB::table('products')
            ->select('id', 'name', 'description', 'price', 'stock', 'image')
            ->where('name', 'like', "%" . $cari . "%")
            ->get();

        // Jika tidak ada hasil, beri pesan bahwa pencarian tidak ditemukan
        if ($products->isEmpty()) {
            $message = "Produk tidak ditemukan.";
        } else {
            $message = null; // Jika ada hasil, pesan kosong
        }

        $categories = DB::table('categories')
            ->select('*')
            ->get();

        // Kembalikan view dengan produk yang ditemukan dan pesan jika ada
        return view('front.menu', compact('products', 'message', 'categories'));
    }

    // all

    // public function makanan()
    // {
    //     $products = DB::table('products')
    //         ->select('*')
    //         ->where('stoks', '=', '1')
    //         ->where('category_id', '=', '9') // Kategori Makanan
    //         ->get();
    //     return view('front.menu', ['products' => $products]);
    // }

    // public function minuman()
    // {
    //     $products = DB::table('products')
    //         ->select('*')
    //         ->where('category_id', '=', '10') // Kategori Minuman
    //         ->where('stoks', '=', '1') // Memastikan hanya produk dengan stok > 0
    //         ->get();
    //     return view('front.menu', ['products' => $products]);
    // }

    // public function promo()
    // {
    //     $products = DB::table('products')
    //         ->select('*')
    //         ->where('stoks', '=', '1') // Hanya produk dengan stok > 0
    //         ->where('category_id', '=', '17') // Kategori Promo
    //         ->get();

    //     return view('front.menu', ['products' => $products]);
    // }
    // public function semua()
    // {
    //     $products = DB::table('products')
    //         ->select('*')
    //         ->where('stoks', '=', '1') // Hanya produk dengan stok > 0
    //         ->get();
    //     return view('front.menu', ['products' => $products]);
    // }

    public function detail_front($id)
    {
        $product = Product::findOrFail($id);
        return view('front.detail_product', compact('product'));
    }
    public function pembayaran($id)
    {
        $order = Order::findOrFail($id);
        return view('front.pembayaran', compact('order'));
    }
}
