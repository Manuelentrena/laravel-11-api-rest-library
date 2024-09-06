<?php

namespace Database\Seeders;

use App\Models\Loan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove the data
        Loan::truncate();

        // Created 3 Genre
        for ($i = 0; $i < 500; $i++) {
            $loanedAt = fake()->dateTimeBetween('-2 years', 'now');
            $returnedAt = fake()->optional()->dateTimeBetween($loanedAt, 'now');

            Loan::create([
                'user_id' => fake()->numberBetween(1, 30),
                'book_id' => fake()->numberBetween(1, 100),
                'loaned_at' => $loanedAt,
                'returned_at' => $returnedAt,
                'overdue_at' => \Carbon\Carbon::instance($loanedAt)->addMonths(3),
                'is_returned' => $returnedAt !== null,
            ]);
        }
    }
}
