<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\RedirectsUsers;

class LoginController extends Controller
{
    use RedirectsUsers;

    protected function authenticated($request, $user)
    {
        return redirect($this->redirectTo());
    }
} 