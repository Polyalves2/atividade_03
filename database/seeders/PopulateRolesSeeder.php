<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Adicione esta linha

class PopulateRolesSeeder extends Seeder
{
    public function run()
    {
        // Criar role de administrador
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'description' => 'Administrador do sistema'
        ]);

        // Migrar usuários antigos (opcional)
        if (Schema::hasColumn('users', 'role')) {
            $adminUsers = DB::table('users')->where('role', 'admin')->pluck('id');
            
            foreach ($adminUsers as $userId) {
                DB::table('role_user')->updateOrInsert(
                    ['user_id' => $userId, 'role_id' => $adminRole->id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // Criar outras roles básicas
        $roles = [
            ['name' => 'librarian', 'description' => 'Bibliotecário'],
            ['name' => 'user', 'description' => 'Usuário comum']
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}