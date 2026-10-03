<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            'products.view',
            'products.create',
            'products.update',
            'products.delete',



            'content.view',
            'content.update',

            'settings.view',
            'settings.update',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findOrCreate('super-admin', 'web');
        $admin = Role::findOrCreate('admin', 'web');
        $editor = Role::findOrCreate('editor', 'web');
        $viewer = Role::findOrCreate('viewer', 'web');

        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            'products.view',
            'products.create',
            'products.update',
            'products.delete',



            'content.view',
            'content.update',

            'settings.view',
            'settings.update',
        ]);

        $editor->syncPermissions([
            'categories.view',

            'products.view',
            'products.create',
            'products.update',



            'content.view',
            'content.update',

            'settings.view',
        ]);

        $viewer->syncPermissions([
            'categories.view',
            'products.view',

            'content.view',
            'settings.view',
        ]);

        $user = User::where('email', 'admin@catalogo.test')->first();

        if ($user) {
            $user->syncRoles([$superAdmin]);
        }
    }
}