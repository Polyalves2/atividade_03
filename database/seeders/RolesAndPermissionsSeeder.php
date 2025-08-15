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

        // Criar permissões
        Permission::firstOrCreate(['name' => 'create books']);
        Permission::firstOrCreate(['name' => 'edit books']);
        // Adicione outras permissões conforme necessário

        // Criar roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Atribuir permissões
        $adminRole->givePermissionTo(Permission::all());
    }
}