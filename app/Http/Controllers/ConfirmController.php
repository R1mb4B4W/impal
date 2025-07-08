<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Confirm;
use App\Models\Order;

class ConfirmController extends Controller
{
    public function index($id)
    {
        $order = Order::findOrFail($id);
        return view('customer.confirm', compact('order'));
    }

    public function store(Request $request)
    {
        $order_id = $request->order_id;
        $confirm = new Confirm;

        // Upload gambar
        $file = $request->file('image');
        $ext = $file->getClientOriginalExtension();
        $newName = rand(100000, 1001238912) . "." . $ext;
        $file->move('upload/confirm', $newName);

        // Pakai helper currentCustomer()
        $customer = currentCustomer();

        if ($customer) {
            $confirm->user_id = $customer->id;
            $order = Order::where('id', $order_id)
                ->where('user_id', $customer->id)
                ->first();
        } else {
            $visitor_id = session('visitor_id');
            $confirm->visitor_id = $visitor_id;
            $order = Order::where('id', $order_id)
                ->where('visitor_id', $visitor_id)
                ->first();
        }

        // Simpan data konfirmasi
        $confirm->order_id = $order_id;
        $confirm->image = $newName;
        $confirm->status_order = 'menunggu verifikasi';
        $confirm->save();

        // Update status order
        if ($order) {
            $order->status = 'menunggu verifikasi';
            $order->save();
        }

        return redirect('/invoice/list')
            ->with('success', 'Pembayaran berhasil, admin akan verifikasi pesananmu!');
    }
}
