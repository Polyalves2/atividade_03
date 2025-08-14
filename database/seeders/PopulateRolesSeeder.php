<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PopulateRolesSeeder extends Seeder
{
    public function run()
    {
        // Cria as roles necessárias, caso não existam
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'bibliotecario']);
        Role::firstOrCreate(['name' => 'cliente']);
    }
}
