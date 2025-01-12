<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Plant Management Permissions
        Permission::create(['name' => 'view plants']);
        Permission::create(['name' => 'add plants']);
        Permission::create(['name' => 'edit plants']);
        Permission::create(['name' => 'delete plants']);
        Permission::create(['name' => 'manage inventory']);

        // Order Management Permissions
        Permission::create(['name' => 'create orders']);
        Permission::create(['name' => 'view orders']);
        Permission::create(['name' => 'update order status']);
        Permission::create(['name' => 'cancel orders']);

        // Review & Rating Permissions
        Permission::create(['name' => 'create reviews']);
        Permission::create(['name' => 'view reviews']);
        Permission::create(['name' => 'manage reviews']);

        // Profile Management Permissions
        Permission::create(['name' => 'manage profile']);
        Permission::create(['name' => 'update preferences']);
        Permission::create(['name' => 'update location']);

        // Cart Management Permissions
        Permission::create(['name' => 'manage cart']);
        Permission::create(['name' => 'checkout']);

        // Create Regular User Role
        Role::create(['name' => 'user'])
            ->givePermissionTo([
                'view plants',
                'create orders',
                'view orders',
                'cancel orders',
                'create reviews',
                'view reviews',
                'manage profile',
                'update preferences',
                'update location',
                'manage cart',
                'checkout'
            ]);

        // Create Seller Role
        Role::create(['name' => 'seller'])
            ->givePermissionTo([
                'view plants',
                'add plants',
                'edit plants',
                'delete plants',
                'manage inventory',
                'view orders',
                'update order status',
                'view reviews',
                'manage reviews',
                'manage profile',
                'update preferences',
                'update location'
            ]);
    }
} 