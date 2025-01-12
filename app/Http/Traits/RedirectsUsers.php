<?php

namespace App\Http\Traits;

trait RedirectsUsers
{
    public function redirectTo()
    {
        if (auth()->user()->hasRole('seller')) {
            return route('seller.dashboard');
        }
        
        if (auth()->user()->hasRole('user')) {
            return route('user.dashboard');
        }
        
        return '/';
    }
} 