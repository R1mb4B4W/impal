<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('id', 'desc')
            ->select('id', 'receiver', 'address', 'total_price', 'date', 'status', 'detail_status')
            ->get();
        return view('admin.order.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver' => 'required|string|max:255',
            'address' => 'required|string|min:10|max:15',
            'total_price' => 'required|integer',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $order = new Order();
        $order->receiver = $request->input('receiver');
        $order->address = $request->input('address');
        $order->total_price = $request->input('total_price');
        $order->status = 'pending';
        $order->detail_status = 'menunggu konfirmasi pembayaran';
        $order->date = now();
        $order->user_id = auth()->id(); // Jika menggunakan autentikasi
        $order->save();

        $order->products()->attach($request->product_id, [
            'quantity' => $request->quantity,
            'subtotal' => $request->quantity * \App\Models\Product::find($request->product_id)->price
        ]);

        return redirect()->route('order.success', ['order' => $order->id])
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    public function produkData(Request $request)
    {
        $query = Order::select('id', 'receiver', 'address', 'total_price', 'date', 'status', 'detail_status')
            ->orderBy('id', 'desc');

        if ($request->has('from_date') && $request->has('to_date') && $request->from_date && $request->to_date) {
            $query->byDateRange($request->from_date, $request->to_date);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('address', fn($row) => $row->address)
            ->addColumn('detail_status', fn($row) => $row->detail_status ?? 'Belum diatur')
            ->addColumn('action', function ($row) {
                $updateStatus = '<form action="' . route('admin.order.updateStatus', $row->id) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . '
                    <select name="detail_status" onchange="this.form.submit()">
                        <option value="">Pilih Status</option>';
                foreach (Order::$deliveryStatuses as $status) {
                    $selected = $row->detail_status == $status ? 'selected' : '';
                    $updateStatus .= "<option value='$status' $selected>$status</option>";
                }
                $updateStatus .= '</select></form>';
                return '<div class="btn-group">
                    <a href="' . route('admin.order.detail', $row->id) . '" class="btn btn-sm btn-primary">Detail</a>
                    <button class="btn btn-sm btn-danger print-btn" data-id="' . $row->id . '">Cetak</button>
                    ' . $updateStatus . '
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function invoiceDetail($id)
    {
        $order = Order::with('products')->findOrFail($id);
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('invoice.list')->with('warning', 'Anda tidak memiliki akses ke pesanan ini.');
        }
        $details = $order->products;
        return view('customer.invoice_detail', compact('order', 'details'));
    }

    public function detail($id)
    {
        $order = Order::with('products')->findOrFail($id);
        $details = $order->products;

        return view('admin.order.detail', compact('order', 'details'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'detail_status' => 'required|in:' . implode(',', Order::$deliveryStatuses)
        ]);

        $order = Order::findOrFail($id);
        $order->detail_status = $request->detail_status;
        $order->save();

        return redirect()->route('admin.order.index')->with('success', 'Status pengiriman diperbarui!');
    }
}
