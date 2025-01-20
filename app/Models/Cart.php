<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Cart
{
    public static function getTotal()
    {
        $cart = Session::get('cart', []);
        return collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public static function getItems()
    {
        return Session::get('cart', []);
    }

    public static function clear()
    {
        Session::forget('cart');
    }
} 