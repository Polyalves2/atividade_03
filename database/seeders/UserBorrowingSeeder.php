<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserBorrowingSeeder extends Seeder
{
    public function run()
    {
        // Get existing users and books to avoid creation conflicts
        $users = User::pluck('id');
        $books = Book::pluck('id');

        if ($users->isEmpty() || $books->isEmpty()) {
            $this->command->warn('Please run User and Book seeders first!');
            return;
        }

        // Create 50 active borrowings with existing users and books
        Borrowing::factory()->count(50)->create([
            'user_id' => function() use ($users) {
                return $users->random();
            },
            'book_id' => function() use ($books) {
                return $books->random();
            },
            'borrowed_at' => Carbon::now()->subDays(rand(1, 30)),
            'expected_return_date' => Carbon::now()->addDays(15),
            'returned_at' => null,
            'status' => 'borrowed',
            'notes' => null,
        ]);

        // Create 20 returned borrowings
        Borrowing::factory()->count(20)->returned()->create([
            'user_id' => function() use ($users) {
                return $users->random();
            },
            'book_id' => function() use ($books) {
                return $books->random();
            },
        ]);

        // Create 10 overdue borrowings
        Borrowing::factory()->count(10)->overdue()->create([
            'user_id' => function() use ($users) {
                return $users->random();
            },
            'book_id' => function() use ($books) {
                return $books->random();
            },
        ]);
    }
}