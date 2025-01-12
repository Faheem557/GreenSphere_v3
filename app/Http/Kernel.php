<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware aliases.
     *
     * Aliases may be used instead of class names to assign middleware to groups or routes.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        // ... other middleware aliases
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
        'redirect.role' => \App\Http\Middleware\RedirectBasedOnRole::class,
        'seller' => \App\Http\Middleware\SellerMiddleware::class,
    ];
}
