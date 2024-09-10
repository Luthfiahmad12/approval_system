<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\ApproveExpenseRequest;
use App\Services\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="Approval System API", version="1.0")
 */
class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * @OA\Post(
     *     path="/api/expenses",
     *     summary="Tambah pengeluaran",
     *     tags={"Expenses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="amount", type="integer", example=1000),
     *                 @OA\Property(property="status_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pengeluaran berhasil ditambahkan",
     *         @OA\JsonContent(ref="#/components/schemas/Expense")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Permintaan tidak valid"
     *     )
     * )
     */
    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = $this->expenseService->newExpense($request->validated());
        return response()->json($expense, 201);
    }

    /**
     * @OA\Patch(
     *     path="/api/expenses/{id}/approve",
     *     summary="Setujui pengeluaran",
     *     tags={"Expenses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="approver_id", type="integer", example=1),
     *                 @OA\Property(property="status_id", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pengeluaran berhasil disetujui",
     *         @OA\JsonContent(ref="#/components/schemas/Expense")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Permintaan tidak valid"
     *     )
     * )
     */
    public function patchApprove(Request $request, $id): JsonResponse
    {
        try {
            $expense = $this->expenseService->approveExpense(
                $id,
                $request->input('approver_id'),
                $request->input('status_id')
            );

            return response()->json($expense, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/expenses/{id}",
     *     summary="Ambil pengeluaran",
     *     tags={"Expenses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pengeluaran berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/Expense")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pengeluaran tidak ditemukan"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $expense = $this->expenseService->getAll()->find($id);
        return response()->json($expense);
    }
}
