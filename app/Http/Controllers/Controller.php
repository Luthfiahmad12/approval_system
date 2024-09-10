<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Expense",
 *     type="object",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="amount", type="integer"),
 *     @OA\Property(property="status_id", type="integer"),
 *     @OA\Property(property="approvals", type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="approver", type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="name", type="string")
 *             ),
 *             @OA\Property(property="status", type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="name", type="string")
 *             )
 *         )
 *     )
 * )
 */
abstract class Controller
{
    //
}
