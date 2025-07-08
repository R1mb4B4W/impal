<?php

namespace App\Http\Controllers;

use App\Models\Confirm;
use App\Models\Order;
use App\Models\Order_Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConfirmAdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $title = 'List Konfirmasi Pembayaran Customer';

        // Ambil semua konfirmasi menunggu verifikasi dari user login maupun guest
        $confirms = Confirm::with('user') // relasi user bisa null
            ->where('status_order', 'menunggu verifikasi')
            ->orderBy('id', 'desc')
            ->get();

        // Ambil konfirmasi pesanan guest dengan visitor_id
        $confirmsGuest = Confirm::with('user')
            ->where('status_order', 'menunggu verifikasi')
            ->whereNull('user_id') // Mengambil guest yang tidak memiliki user_id
            ->whereNotNull('visitor_id') // Pastikan ada visitor_id
            ->orderBy('id', 'desc')
            ->get();

        // Gabungkan hasil konfirmasi dari guest dan user
        $confirms = $confirms->merge($confirmsGuest);

        return view('confirm.index', compact('confirms', 'title'));
    }

    public function terima($order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->status = 'dibayar';
        $order->save();

        $confirm = Confirm::where('order_id', $order_id)->first();
        if ($confirm) {
            $confirm->status_order = 'dibayar';
            $confirm->save();
        }

        Session::flash('status', 'Berhasil dikonfirmasi dengan status DITERIMA');
        return redirect()->route('confirmAdmin');
    }

    public function tolak(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        if ($order->status === 'dibayar') {
            return redirect()->route('confirmAdmin')->with('error', 'Pesanan sudah dibayar, tidak bisa ditolak!');
        }

        $order->status = 'ditolak';
        $order->detail_status = $request->input('detail_status'); // opsional, bisa dikosongkan
        $order->save();

        $confirm = Confirm::where('order_id', $order_id)->first();
        if ($confirm) {
            $confirm->status_order = 'ditolak';
            $confirm->save();
        }

        Session::flash('status', 'Berhasil dikonfirmasi dengan status DITOLAK');
        return redirect()->route('confirmAdmin');
    }

    public function detail($id)
    {
        $details = Order_Product::where('order_id', $id)->get();
        $identity = Order_Product::where('order_id', $id)->first();
        return view('confirm.detail', compact('details', 'identity'));
    }
}
