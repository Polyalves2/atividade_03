<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetAndSeedTablesSeeder extends Seeder
{
    public function run(): void
    {
        // Tabelas a serem limpas (exceto users)
        $tables = ['categories', 'authors', 'publishers', 'books', 'borrowings'];

        foreach ($tables as $table) {
            DB::table($table)->truncate(); // Limpa a tabela e reinicia IDs
        }

        $this->command->info('Tabelas limpas com sucesso!');

        // Populando Categories
        DB::table('categories')->insert([
            ['name' => 'Ficção'],
            ['name' => 'Não Ficção'],
            ['name' => 'Terror'],
            ['name' => 'Romance'],
            ['name' => 'Fantasia'],
            ['name' => 'Biografia'],
            ['name' => 'História'],
            ['name' => 'Infantil'],
        ]);

        // Populando Authors
        DB::table('authors')->insert([
            ['name' => 'Autor 1'],
            ['name' => 'Autor 2'],
            ['name' => 'Autor 3'],
            ['name' => 'Autor 4'],
        ]);

        // Populando Publishers
        DB::table('publishers')->insert([
            ['name' => 'Editora A'],
            ['name' => 'Editora B'],
            ['name' => 'Editora C'],
        ]);

        // Populando Books
        DB::table('books')->insert([
            ['title' => 'Livro 1', 'category_id' => 1, 'author_id' => 1, 'publisher_id' => 1],
            ['title' => 'Livro 2', 'category_id' => 2, 'author_id' => 2, 'publisher_id' => 2],
            ['title' => 'Livro 3', 'category_id' => 3, 'author_id' => 3, 'publisher_id' => 3],
            ['title' => 'Livro 4', 'category_id' => 1, 'author_id' => 4, 'publisher_id' => 1],
        ]);

        // Populando Borrowings
        DB::table('borrowings')->insert([
            [
                'user_id' => 1,
                'book_id' => 1,
                'borrowed_at' => now(),
                'expected_return_date' => now()->addDays(15),
                'returned_at' => null,
            ],
            [
                'user_id' => 1,
                'book_id' => 2,
                'borrowed_at' => now(),
                'expected_return_date' => now()->addDays(15),
                'returned_at' => now()->addDays(5),
            ],
        ]);

        $this->command->info('Tabelas populadas com sucesso!');
    }
}
