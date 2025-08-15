<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Limpa a tabela e reinicia o auto-increment
        DB::table('categories')->truncate();

        $categories = [
            'Ficção',
            'Não Ficção',
            'Terror',
            'Romance',
            'Fantasia',
            'Biografia',
            'História',
            'Infantil'
        ];

        // Popula novamente
        foreach ($categories as $category) {
            Category::create([
                'name' => $category
            ]);
        }
    }
}
