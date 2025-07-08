<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $order_product = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->select('order_product.*', 'orders.*')
            ->get();

        return view('customer.invoice', ['order_product' => $order_product]);
    }

    public function list()
    {
        $customer = currentCustomer(); // pakai helper kamu

        if ($customer) {
            // Ambil pesanan berdasar user_id customer
            $orders = Order::where('user_id', $customer->id)
                ->orderBy('status', 'asc')
                ->get();
        } else {
            // Jika bukan customer / guest, ambil berdasar visitor_id
            $visitor_id = session('visitor_id');
            $orders = Order::where('visitor_id', $visitor_id)
                ->orderBy('status', 'asc')
                ->get();
        }

        return view('customer.list_invoice', compact('orders'));
    }

    public function detail($id)
    {
        $customer = currentCustomer();
        $visitor_id = session('visitor_id');

        $details = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->select('order_product.*', 'orders.*', 'products.*')
            ->where('order_product.order_id', $id)
            ->where(function ($query) use ($customer, $visitor_id) {
                if ($customer) {
                    $query->where('orders.user_id', $customer->id);
                } else {
                    $query->where('orders.visitor_id', $visitor_id);
                }
            })
            ->get();

        if ($details->isEmpty()) {
            abort(404, 'Pesanan tidak ditemukan atau tidak memiliki akses.');
        }

        return view('customer.invoice_detail', compact('details'));
    }
}
