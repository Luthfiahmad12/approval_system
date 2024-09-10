<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_handle_expense_approval_flow()
    {
        $approverData = [
            ['name' => 'Ana'],
            ['name' => 'Ani'],
            ['name' => 'Ina']
        ];

        $approvers = [];
        foreach ($approverData as $data) {
            $response = $this->postJson('/api/approvers', $data);
            $response->assertStatus(201);
            $approvers[] = $response->json('id');
        }

        $approvalStageData = [];
        foreach ($approvers as $approverId) {
            $response = $this->postJson('/api/approval-stages', [
                'approver_id' => $approverId
            ]);
            $response->assertStatus(201);
            $approvalStageData[] = $response->json('id');
        }

        $statusPendingResponse = $this->postJson('/api/statuses', ['name' => 'menunggu persetujuan']);
        $statusPendingResponse->assertStatus(201);
        $statusPendingId = $statusPendingResponse->json('id');

        $statusApprovedResponse = $this->postJson('/api/statuses', ['name' => 'disetujui']);
        $statusApprovedResponse->assertStatus(201);
        $statusApprovedId = $statusApprovedResponse->json('id');

        // Create 4 expenses
        $expenses = [];
        for ($i = 1; $i <= 4; $i++) {
            $response = $this->postJson('/api/expenses', [
                'amount' => 1000 * $i,
                'status_id' => $statusPendingId
            ]);
            $response->assertStatus(201);
            $expenses[] = $response->json('id');
        }

        foreach ($approvers as $approverId) {
            $this->patchJson('/api/expenses/' . $expenses[0] . '/approve', [
                'approver_id' => $approverId,
                'status_id' => $statusApprovedId,
            ])->assertStatus(200);
        }

        $response = $this->getJson('/api/expenses/' . $expenses[0]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => [
                'id' => $statusApprovedId,
                'name' => 'disetujui'
            ]
        ]);

        $this->patchJson('/api/expenses/' . $expenses[1] . '/approve', [
            'approver_id' => $approvers[0],
            'status_id' => $statusApprovedId,
        ])->assertStatus(200);

        $response = $this->getJson('/api/expenses/' . $expenses[1]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => [
                'id' => $statusPendingId,
                'name' => 'menunggu persetujuan'
            ]
        ]);

        $this->patchJson('/api/expenses/' . $expenses[2] . '/approve', [
            'approver_id' => $approvers[0],
            'status_id' => $statusApprovedId,
        ])->assertStatus(200);

        $response = $this->getJson('/api/expenses/' . $expenses[2]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => [
                'id' => $statusPendingId,
                'name' => 'menunggu persetujuan'
            ]
        ]);

        $response = $this->getJson('/api/expenses/' . $expenses[3]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => [
                'id' => $statusPendingId,
                'name' => 'menunggu persetujuan'
            ]
        ]);
    }
}
