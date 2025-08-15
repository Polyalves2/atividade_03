<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
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
            [
                'user_id' => 2,
                'book_id' => 3,
                'borrowed_at' => now()->subDays(10),
                'expected_return_date' => now()->subDays(10)->addDays(15),
                'returned_at' => null,
            ],
        ]);
    }
}
