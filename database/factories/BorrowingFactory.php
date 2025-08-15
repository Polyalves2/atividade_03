<?php

namespace Database\Factories;

use App\Models\Borrowing;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BorrowingFactory extends Factory
{
    protected $model = Borrowing::class;

    public function definition(): array
    {
        $borrowedAt = Carbon::now()->subDays(rand(1, 30));
        $expectedReturn = $borrowedAt->copy()->addDays(rand(7, 15));
        $isReturned = $this->faker->optional(0.3)->dateTimeBetween(
            $borrowedAt, 
            $expectedReturn->copy()->addDays(15)
        );

        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'borrowed_at' => $borrowedAt,
            'expected_return_date' => $expectedReturn,
            'returned_at' => $isReturned,
            'status' => $isReturned ? 'returned' : 
                       (Carbon::now()->gt($expectedReturn) ? 'overdue' : 'borrowed'),
            'notes' => $this->faker->optional(0.2)->sentence(),
            'created_at' => $borrowedAt,
            'updated_at' => $isReturned ? Carbon::parse($isReturned) : now(),
        ];
    }

    public function returned(): static
    {
        return $this->state(function (array $attributes) {
            $returnedAt = Carbon::parse($attributes['borrowed_at'])
                          ->addDays(rand(1, $attributes['expected_return_date']->diffInDays($attributes['borrowed_at']) + 5));

            return [
                'returned_at' => $returnedAt,
                'status' => 'returned',
                'updated_at' => $returnedAt
            ];
        });
    }

    public function overdue(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'expected_return_date' => Carbon::now()->subDays(rand(1, 15)),
                'returned_at' => null,
                'status' => 'overdue',
                'updated_at' => now()
            ];
        });
    }

    public function active(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'expected_return_date' => Carbon::now()->addDays(rand(1, 14)),
                'returned_at' => null,
                'status' => 'borrowed'
            ];
        });
    }
}