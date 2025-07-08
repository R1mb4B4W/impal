<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Confirm;


class AdminController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth')->except(['showLoginForm', 'login']);
    // }

    // Menampilkan form login admin
    public function showLoginForm()
    {
        return view('admin.auth.login'); // Pastikan file ini ada
    }

    // Proses login admin
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil');
        }

        return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
    }

    // Logout admin
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Logout berhasil');
    }

    // Menampilkan dashboard admin
    public function dashboard() {
        $orders = Order::all(); // Ambil semua pesanan
        $products = Product::all(); // Ambil semua produk
        $confirm = Confirm::all(); // Ambil semua konfirmasi pesanan

        return view('admin.dashboard', compact('orders', 'products', 'confirm')); 
    }

    // Menampilkan profil admin
    public function profil() {
        return view('admin.profil');
    }

    // Menyimpan perubahan password
    public function store(Request $request) {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('admin.profil')->with('success', 'Password berhasil diubah');
    }
}