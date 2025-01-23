<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//*********************** REQUEST İŞLEMLERİ ***********************
Route::prefix('requests')->group(function (){
    Route::post('/create',[LeaveRequestController::class,'store'])->name('leave-request-create');
    Route::get('/list',[LeaveRequestController::class,'index'])->name('leave-request-list');
    Route::put('/{id}/confirm',[LeaveRequestController::class,'confirm'])->name('leave-request-confirm')->middleware('auth:sanctum');;
    Route::put('/{id}/reject',[LeaveRequestController::class,'reject'])->name('leave-request-reject')->middleware('auth:sanctum');;
});
//*********************** LOGİN İŞLEMLERİ ***********************
Route::post('/login', [AuthController::class, 'login']);

