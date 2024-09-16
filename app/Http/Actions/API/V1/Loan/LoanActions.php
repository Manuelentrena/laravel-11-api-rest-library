<?php

namespace App\Http\Actions\API\V1\Loan;

use App\Exceptions\API\V1\UpdateLoanException;
use App\Http\Requests\API\V1\Loan\StoreLoanRequest;
use App\Exceptions\API\V1\CreateLoanException;
use App\Models\Loan;
use DB;

class LoanActions
{
    public function createLoanAndDecreaseStock(StoreLoanRequest $request): Loan
    {
        return DB::transaction(function () use ($request) {
            $loan = Loan::create([
                'book_id' => $request->book_id,
                'loaned_at' => now(),
                'overdue_at' => now()->addDays(config('library.days_of_loans')),
                'returned_at' => null,
                'is_returned' => false,
            ]);

            if (!$loan) {
                throw new CreateLoanException();
            }

            $isStockUpdate = $this->decreaseStock($loan);

            if (!$isStockUpdate) {
                throw new CreateLoanException();
            }

            return $loan;
        });
    }

    public function decreaseStock(Loan $loan): bool
    {
        return $loan->book->update([
            'stock' => $loan->book->stock - config('library.less_stock'),
        ]);
    }

    public function updateLoanAndAddStock(Loan $loan)
    {
        return DB::transaction(function () use ($loan) {
            $isLoanUpdate = $loan->update([
                'returned_at' => now(),
                'is_returned' => true,
            ]);

            if (!$isLoanUpdate) {
                throw new UpdateLoanException();
            }

            $isStockUpdate = $this->increaseStock($loan);

            if (!$isStockUpdate) {
                throw new UpdateLoanException();
            }

            return $loan;
        });
    }

    public function increaseStock($loan): bool
    {
        return $loan->book->update([
            'stock' => $loan->book->stock + config('library.more_stock'),
        ]);
    }
}
