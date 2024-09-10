<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStatusRequest;
use App\Services\StatusService;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    protected $status;

    public function __construct(StatusService $statusService)
    {
        $this->status = $statusService;
    }
    public function store(StoreStatusRequest $request)
    {
        $status = $this->status->newStatus($request->validated());
        return response()->json($status, 201);
    }
}
