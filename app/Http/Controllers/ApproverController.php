<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApproverRequest;
use App\Services\ApproverService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApproverController extends Controller
{
    protected $approverService;

    public function __construct(ApproverService $approverService)
    {
        $this->approverService = $approverService;
    }

    public function store(StoreApproverRequest $request): JsonResponse
    {
        $approver = $this->approverService->newApprover($request->validated());

        return response()->json($approver, 201);
    }
}
