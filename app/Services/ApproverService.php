<?php

namespace App\Services;

use App\Models\Approver;

class ApproverService
{
    public function newApprover($data)
    {
        return Approver::create($data);
    }
}
