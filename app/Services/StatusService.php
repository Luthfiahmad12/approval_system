<?php

namespace App\Services;

use App\Models\Status;

class StatusService
{
    public function newStatus(array $data)
    {
        return Status::create($data);
    }
}
