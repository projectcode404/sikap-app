<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->unique();
            $table->string('name');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('work_unit_id')->nullable();
            $table->enum('level', ['operative','staff','supervisor','manager']);
            $table->enum('employment_type', ['permanent','contract']);
            $table->string('vendor_name')->nullable();
            $table->date('in_date')->nullable();
            $table->date('out_date')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
