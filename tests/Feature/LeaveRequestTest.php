<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeaveRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase; // Veritabanını her testten önce sıfırlamak için

    public function test_create_leave_request()
    {
        $employee = Employee::factory()->create();

        $data = [
            'employee_id' => 1,
            'start_date' => '2025-01-25',
            'end_date' => '2025-01-30',
        ];

        $response = $this->postJson(route('leave-request-create'), $data);

        $response->assertStatus(201); // Veya başarılı bir yanıt durumu
        $this->assertDatabaseHas('leaves-requests', $data); // Veritabanını kontrol et
    }
}
