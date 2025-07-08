<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Order_Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class CartController extends Controller
{
    public function cartList()
    {
        $cartItems = \Cart::getContent();
        return view('front.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        \Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => [
                'image' => $request->image,
            ]
        ]);

        session()->flash('success', 'Produk berhasil ditambahkan ke dalam keranjang!');
        return redirect()->route('cart.list');
    }

    public function updateCart(Request $request)
    {
        \Cart::update($request->id, [
            'quantity' => [
                'relative' => false,
                'value' => $request->quantity
            ],
        ]);

        session()->flash('success', 'Produk berhasil diperbarui!');
        return redirect()->route('cart.list');
    }

    public function removeCart(Request $request)
    {
        \Cart::remove($request->id);
        session()->flash('success', 'Produk berhasil dihapus dari keranjang!');
        return redirect()->route('cart.list');
    }

    public function clearAllCart()
    {
        \Cart::clear();
        session()->flash('success', 'Keranjang berhasil dikosongkan!');
        return redirect()->route('cart.list');
    }

    public function checkout(Request $request)
    {
        if (\Cart::getTotalQuantity() < 1) {
            return redirect()->route('cart.list')->with('warning', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        return view('customer.checkout');
    }

    public function guestLogin(Request $request)
    {
        session(['visitor_id' => uniqid()]);
        return redirect()->route('cart.checkout')->with('success', 'Anda melanjutkan sebagai tamu.');
    }

    public function bayar(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'pickup_time' => 'required|date',
        ]);

        $customer = currentCustomer();
        $user_id = $customer ? $customer->id : null;
        $visitor_id = $customer ? null : session('visitor_id');

        $receiver = $customer ? $customer->name : $request->name;
        $address = $request->address;
        $catatan = $request->catatan ?? 'Tidak Ada Catatan';
        $pickup_time = $request->pickup_time;
        $total_bayar = \Cart::getTotal();
        $keranjang = \Cart::getContent();

        // Simpan session guest (opsional jika ingin diisi otomatis nanti)
        if (!$customer) {
            session([
                'guest_name' => $receiver,
                'guest_address' => $address,
                'pickup_time' => $pickup_time,
            ]);
        }

        $order = new Order();
        $order->user_id = $user_id;
        $order->visitor_id = $visitor_id;
        $order->receiver = $receiver;
        $order->address = $address;
        $order->catatan = $catatan;
        $order->detail_status = 'belum bayar';
        $order->status = 'belum bayar';
        $order->total_price = $total_bayar;
        $order->pickup_time = $pickup_time;
        $order->date = Carbon::now();
        $order->save();

        foreach ($keranjang as $cart) {
            Order_Product::create([
                'order_id' => $order->id,
                'product_id' => $cart->id,
                'quantity' => $cart->quantity,
                'subtotal' => $cart->quantity * $cart->price,
            ]);
        }

        \Cart::clear();
        session()->forget(['receiver', 'address', 'catatan', 'pickup_time']);

        return redirect()->route('pembayaran', ['id' => $order->id])
            ->with('success', 'Pemesanan berhasil. Silakan lanjut ke pembayaran.');
    }
}
