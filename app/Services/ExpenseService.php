<?php

namespace App\Services;

use App\Models\ApprovalStage;
use App\Models\Approver;
use App\Models\Expense;
use App\Models\Status;

class ExpenseService
{
    public function newExpense(array $data)
    {
        return Expense::create($data);
    }

    private function validateApprovalStage(int $approverId): void
    {
        if (!ApprovalStage::where('approver_id', $approverId)->exists()) {
            throw new \Exception('Approver tidak berada dalam tahap approval');
        }
    }

    public function approveExpense(int $expenseId, int $approverId, int $statusId): Expense
    {
        $expense = Expense::findOrFail($expenseId);

        $this->validateApprovalStage($approverId);

        $expense->approvals()->UpdateOrCreate([
            'approver_id' => $approverId,
            'status_id' => $statusId,
        ]);

        $allApproversApproved = $expense->approvals()->count() === ApprovalStage::count();
        $expense->status_id = $allApproversApproved ? $statusId : $expense->status_id;
        $expense->save();

        return $expense;
    }

    public function getAll()
    {
        return Expense::with('status')->get();
    }
}
