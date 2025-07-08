<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    public function handle(Request $request, Closure $next, $roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = Auth::user()->role;

        if ($userRole == $roles) {
            return $next($request);
        }

        return redirect('/')->with('error', "Anda Tidak Punya Akses Untuk Login");
    }
}


// public function handle(Request $request, Closure $next, $roles): Response
// {
//     if (!Auth::check()) {
//         return redirect('login');
//     }
//     //$user = Auth::user();

//     $userRole = Auth::user()->roles->role_name;

//     if ($userRole == $roles)
//         return $next($request);

//     return redirect('/')->with('error', "Anda Tidak Punya Akses Untuk Login");
// }
