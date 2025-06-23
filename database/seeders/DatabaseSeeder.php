<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    // public function run(): void
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            AuthorPublisherBookSeeder::class,
            UserBorrowingSeeder::class, // Novo seeder adicionado aqui
        ]);
    }
}