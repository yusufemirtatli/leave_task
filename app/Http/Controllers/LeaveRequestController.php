<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leaves;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Requestleri tablomuzdan çekiyoruz.
        $request = DB::table('leaves-requests')->get();

        // Verileri JSON formatında döndürüyoruz
        return response()->json([
            'success' => true,
            'data' => $request,
            'message' => 'Leaves requests retrieved successfully.',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'employee_id' => 'required|integer|exists:employees,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'status' => 'nullable|in:' . implode(',', array_column(\App\Enums\LeaveRequestStatus::cases(), 'value')),
            ]);

            $startDate = Carbon::parse($validatedData['start_date']);
            $endDate = Carbon::parse($validatedData['end_date']);
            //Gün hesapla.
            $days = $startDate->diffInDays($endDate);
            // Çalışanın annual_leave_days değerini al
            $employee = DB::table('employees')->where('id', $validatedData['employee_id'])->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.',
                ], 404);
            }

            // İzin günlerinin yeterliliğini kontrol et
            if ($employee->annual_leave_days < $days) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient leave days. You have only ' . $employee->annual_leave_days . ' leave days available.',
                ], 400);
            }

            // Aynı tarih aralığında bir izin talebi olup olmadığını kontrol et
            $existingRequest = DB::table('leaves-requests')
                ->where('employee_id', $validatedData['employee_id'])
                ->where(function($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function($query) use ($startDate, $endDate) {
                            $query->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })
                ->exists();

            if ($existingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'There is already a leave request for the given period.',
                ], 400);
            }

            $leaveRequestId = DB::table('leaves-requests')->insertGetId([
                'employee_id' => $validatedData['employee_id'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'day' => $days,
                'status' => $validatedData['status'] ?? \App\Enums\LeaveRequestStatus::PENDING->value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            $leaveRequest = DB::table('leaves-requests')->where('id', $leaveRequestId)->first();

            return response()->json([
                'success' => true,
                'data' => $leaveRequest,
                'message' => 'Leave request created successfully.',
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Error creating leave request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating leave request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function confirm($id)
    {
        try {
            // Kullanıcı doğrulaması
            $user = auth()->user();
            if (!$user || $user->role_id !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to confirm leave requests.',
                ], 403);
            }

            // Request doğrulaması
            $leaveRequest = DB::table('leaves-requests')->where('id', $id)->first();
            if (!$leaveRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found.',
                ], 404);
            }

            // Status güncellemesi
            DB::table('leaves-requests')->where('id', $id)->update([
                'status' => \App\Enums\LeaveRequestStatus::APPROVED->value,
                'updated_at' => now(),
            ]);
            $requestEmployee = Employee::find($leaveRequest->employee_id);
            $requestEmployee->annual_leave_days = $requestEmployee->annual_leave_days - $leaveRequest->day;
            $requestEmployee->save();

            return response()->json([
                'success' => true,
                'message' => 'Leave request confirmed successfully.',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error confirming leave request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error confirming leave request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function reject($id){
        try {
            // Kullanıcı doğrulaması
            $user = auth()->user();
            if (!$user || $user->role_id !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to confirm leave requests.',
                ], 403);
            }

            // Request doğrulaması
            $leaveRequest = DB::table('leaves-requests')->where('id', $id)->first();
            if (!$leaveRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found.',
                ], 404);
            }

            // Status güncellemesi
            DB::table('leaves-requests')->where('id', $id)->update([
                'status' => \App\Enums\LeaveRequestStatus::REJECTED->value,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave request rejected successfully.',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error confirming leave request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error confirming leave request.',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

}
