<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('currentCustomer')) {
    function currentCustomer()
    {
        if (Auth::check() && Auth::user()->role === 'customer') {
            return Auth::user();
        }

        return null;
    }
}
