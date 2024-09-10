<?php

namespace App\Services;

use App\Models\ApprovalStage;

class ApprovalStageService
{
    public function newApprovalStage(array $data)
    {
        return ApprovalStage::create($data);
    }

    public function updateApprovalStage(int $id, array $data)
    {
        $approvalStage = ApprovalStage::findOrFail($id);
        $approvalStage->update($data);
        return $approvalStage;
    }
}
