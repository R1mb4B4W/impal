<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login_process(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $credential = $request->only('email', 'password');

        if (Auth::attempt($credential)) {
            $user = Auth::user(); // Mendapatkan objek user

            // dd($user);
            // Cek role langsung dari objek user
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard'); // Mengarahkan ke dashboard admin
            } elseif ($user->role == 'owner') {
                return redirect()->route('owner.index'); // Atur rute sesuai dengan role
            } elseif ($user->role == 'customer') {
                return redirect()->route('welcome'); // Atur rute sesuai dengan role
            } else {
                abort(403, 'Unauthorized.');
            }

            return redirect()->intended('/');
        }
        // Jika autentikasi gagal, kembali ke halaman login dengan pesan error
        return redirect('login')
            ->withInput()
            ->withErrors(['login_gagal' => 'Username atau Password Anda Salah!']);
    }


    public function logout(Request $request)
    {
        Auth::logout(); // Keluar dari sistem

        $request->session()->invalidate(); // Hapus semua sesi agar tidak bisa digunakan kembali
        $request->session()->regenerateToken(); // Regenerasi CSRF token untuk keamanan

        // Menambahkan pesan flash untuk logout sukses
        $request->session()->flash('success', 'Anda berhasil logout!');

        return redirect()->route('login');
    }

    // public function showAdminLoginForm()
    // {
    //     return view('auth.login-admin');
    // }

    // public function showOwnerLoginForm()
    // {
    //     return view('auth.login-owner');
    // }

    // public function ownerLogin(Request $request)
    // {
    //     $this->validateLogin($request, [
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $credentials = $request->only('email', 'password');
    //     if (Auth::attempt($credentials)) {
    //         if (Auth::user()->role === 'owner') {
    //             return redirect()->intended('/owner/index');
    //         }
    //         Auth::logout();
    //         return back()->withErrors(['email' => 'Hanya owner yang bisa login di sini']);
    //     }
    //     return back()->withErrors(['email' => 'Email atau password salah']);
    // }

    // protected function validateLogin(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:8',
    //     ]);
    // }


}
