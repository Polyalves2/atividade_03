<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            // 1. Configurações básicas do sistema
            RolesAndPermissionsSeeder::class,
            
            // 2. Tabelas independentes
            CategorySeeder::class,
            AuthorSeeder::class,
            PublisherSeeder::class,
            
            // 3. Usuários
            // UserSeeder::class,
            
            // 4. Livros (dependem de categorias, autores e editoras)
            BookSeeder::class,
            
            // 5. Empréstimos (dependem de usuários e livros)
            UserBorrowingSeeder::class
        ]);
    }
}