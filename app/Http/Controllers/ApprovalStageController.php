<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApprovalStageRequest;
use App\Http\Requests\UpdateApprovalStageRequest;
use App\Services\ApprovalStageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApprovalStageController extends Controller
{
    protected $approvalStageService;

    public function __construct(ApprovalStageService $approvalStageService)
    {
        $this->approvalStageService = $approvalStageService;
    }

    public function store(StoreApprovalStageRequest $request): JsonResponse
    {
        $approvalStage = $this->approvalStageService->newApprovalStage($request->validated());
        return response()->json($approvalStage, 201);
    }

    public function update(UpdateApprovalStageRequest $request, int $id): JsonResponse
    {
        $approvalStage = $this->approvalStageService->updateApprovalStage($id, $request->validated());
        return response()->json($approvalStage, 200);
    }
}
