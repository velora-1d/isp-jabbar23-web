<?php

namespace App\Services\Finance;

use App\Models\Expense;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExpenseService
{
    /**
     * Record a new expense.
     */
    public function createExpense(array $data, ?UploadedFile $receipt = null): Expense
    {
        if ($receipt) {
            $data['receipt_path'] = $receipt->store('receipts', 'public');
        }

        $data['created_by'] = auth()->user()->id;

        return Expense::create($data);
    }

    /**
     * Update an existing expense.
     */
    public function updateExpense(Expense $expense, array $data, ?UploadedFile $receipt = null): Expense
    {
        if ($receipt) {
            // Delete old receipt
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $data['receipt_path'] = $receipt->store('receipts', 'public');
        }

        $expense->update($data);

        return $expense;
    }
}
