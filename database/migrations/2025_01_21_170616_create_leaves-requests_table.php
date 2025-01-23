<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leaves-requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('day');
            $leavesStatusValues = array_column(\App\Enums\LeaveRequestStatus::cases(), 'value'); // ENUM YAPISI KULLANILDI
            $table->enum('status',$leavesStatusValues)->default(\App\Enums\LeaveRequestStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves-requests');
    }
};
