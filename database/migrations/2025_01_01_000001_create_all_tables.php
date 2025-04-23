<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('work_units', function (Blueprint $table) {
            $table->string('work_unit_id')->primary();
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->string('employee_id')->primary();
            $table->string('full_name');
            $table->text('address');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['Male','Female'])->default('Male');
            $table->enum('religion', ['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'])->default('Islam');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('ktp_number')->unique()->nullable();
            $table->string('npwp_number')->unique()->nullable();
            $table->string('bpjs_health')->nullable();
            $table->string('bpjs_employee')->nullable();
            $table->string('education')->nullable();
            $table->string('major')->nullable();
            $table->foreignId('position_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('division_id')->nullable()->constrained()->onDelete('set null');
            $table->string('work_unit_id')->nullable()->index();
            $table->foreign('work_unit_id')->references('work_unit_id')->on('work_units')->onDelete('set null');
            $table->enum('level', ['operative','staff','supervisor','manager'])->nullable();
            $table->enum('grade', ['A1','A2','A3','B1','B2','B3','C1','C2','C3','D1','D2','D3','E1','E2'])->nullable();
            $table->enum('employment_type', ['permanent','contract'])->default('permanent');
            $table->enum('vendor', ['IAP','OS'])->default('IAP');
            $table->date('in_date')->nullable();
            $table->date('retirement_date')->nullable();
            $table->date('out_date')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employee_id');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->string('password');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('stock_atk', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('stock_qty');
            $table->string('unit')->default('pcs');
            $table->integer('min_stock')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('po_atk', function (Blueprint $table) {
            $table->id();
            $table->uuid('created_by');
            $table->date('po_date');
            $table->timestamps();
        });

        Schema::create('po_atk_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_atk_id')->constrained('po_atk')->onDelete('cascade');
            $table->foreignId('stock_atk_id')->constrained('stock_atk');
            $table->integer('qty');
            $table->timestamps();
        });

        Schema::create('receive_atk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_atk_id')->constrained('po_atk');
            $table->uuid('received_by');
            $table->date('receive_date');
            $table->timestamps();
        });

        Schema::create('receive_atk_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receive_atk_id')->constrained('receive_atk')->onDelete('cascade');
            $table->foreignId('stock_atk_id')->constrained('stock_atk');
            $table->integer('qty');
            $table->timestamps();
        });

        Schema::create('atk_out_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employee_id');
            $table->string('employee_name');
            $table->string('position_name');
            $table->string('work_unit_id')->nullable();
            $table->foreign('work_unit_id')->references('work_unit_id')->on('work_units')->onDelete('set null');
            $table->uuid('created_by');
            $table->uuid('approved_by')->nullable();
            $table->string('period');
            $table->string('file_path')->nullable();
            $table->string('file_hash')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('atk_out_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('atk_out_request_id');
            $table->foreign('atk_out_request_id')->references('id')->on('atk_out_requests')->onDelete('cascade');
            $table->foreignId('stock_atk_id')->constrained('stock_atk');
            $table->integer('qty');
            $table->timestamps();
        });

        Schema::create('atk_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_atk_id')->constrained('stock_atk');
            $table->string('work_unit_id')->nullable();
            $table->foreign('work_unit_id')->references('work_unit_id')->on('work_units')->onDelete('set null');
            $table->integer('qty_returned');
            $table->date('date');
            $table->string('reason')->nullable();
            $table->uuid('uploaded_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atk_returns');
        Schema::dropIfExists('atk_out_items');
        Schema::dropIfExists('atk_out_requests');
        Schema::dropIfExists('receive_atk_items');
        Schema::dropIfExists('receive_atk');
        Schema::dropIfExists('po_atk_items');
        Schema::dropIfExists('po_atk');
        Schema::dropIfExists('stock_atk');
        Schema::dropIfExists('users');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('work_units');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('divisions');
        Schema::dropIfExists('positions');
    }
};