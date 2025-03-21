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
            $table->string('full_name');
            $table->text('address');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['Male','Female']);
            $table->enum('religion', ['Islam','Kristen','Katolik','Hindu','Budha','Konghucu','Lainnya'])->default('Islam');
            $table->string('education')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('ktp_number')->unique()->nullable();
            $table->string('npwp_number')->unique()->nullable();
            $table->string('bpjs_health')->nullable();
            $table->string('bpjs_employee')->nullable();
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('work_unit_id')->nullable();
            $table->enum('level', ['operative','staff','supervisor','manager'])->nullable();
            $table->enum('employment_type', ['permanent','contract']);
            $table->string('vendor_name')->nullable();
            $table->date('in_date')->nullable();
            $table->date('out_date')->nullable();
            $table->text('notes')->nullable();
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
