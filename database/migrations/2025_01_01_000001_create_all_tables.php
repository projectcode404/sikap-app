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
            $table->string('id')->primary();
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('location')->nullable(); 
            $table->enum('type', ['stock_point', 'depo', 'dc', 'office'])->default('stock_point');
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('full_name');
            $table->text('address');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['male','female'])->default('male');
            $table->enum('religion', ['islam','kristen','katolik','hindu','budha','konghucu'])->default('islam');
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
            $table->foreign('work_unit_id')->references('id')->on('work_units')->onDelete('set null');
            $table->enum('level', ['operative','staff','supervisor','manager'])->nullable();
            $table->enum('grade', ['a1','a2','a3','b1','b2','b3','c1','c2','c3','d1','d2','d3','e1','e2'])->nullable();
            $table->enum('employment_type', ['permanent','contract'])->default('permanent');
            $table->enum('vendor', ['iap','os'])->default('iap');
            $table->date('in_date')->nullable();
            $table->date('retirement_date')->nullable();
            $table->date('out_date')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->text('note')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->string('password');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         
            $table->string('pic')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->text('address')->nullable();        
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('atk_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('unit')->default('pcs');
            $table->integer('current_stock')->default(0);
            $table->unsignedInteger('min_stock')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('atk_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_item_id')->constrained('atk_items');
            $table->enum('type', ['in', 'out', 'adjustment'])->default('in');
            $table->integer('qty');
            $table->integer('begining_stock');
            $table->integer('ending_stock');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('atk_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->string('po_number')->unique()->index();
            $table->date('po_date');
            $table->date('schedule_date')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['open', 'partial', 'received', 'completed', 'canceled'])->default('open');
            $table->string('po_file')->nullable();
            $table->string('receipt_number')->unique()->nullable();
            $table->string('created_by'); // user->employee_id
            $table->timestamps();
        });

        Schema::create('atk_purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_purchase_order_id')->constrained('atk_purchase_orders')->onDelete('cascade');
            $table->foreignId('atk_item_id')->constrained('atk_items');
            $table->integer('qty')->default(0);
            $table->string('unit')->default('pcs');
            $table->integer('received_qty')->default(0);
            $table->timestamps();
        });

        Schema::create('atk_receives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_purchase_order_id')->constrained('atk_purchase_orders');
            $table->string('received_by'); // user->employee_id
            $table->date('receive_date');
            $table->text('note')->nullable();
            $table->string('receipt_file')->nullable();
            $table->timestamps();
        });

        Schema::create('atk_receive_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_purchase_order_item_id')->nullable()->constrained('atk_purchase_order_items');
            $table->foreignId('atk_receive_id')->constrained('atk_receives')->onDelete('cascade');
            $table->foreignId('atk_item_id')->constrained('atk_items');
            $table->integer('qty')->default(0);
            $table->timestamps();
        });

        Schema::create('atk_out_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Identitas peminta
            $table->string('employee_id'); // FK ke employee_id (bukan PK id)
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->string('position_name'); // Biar tetap terlihat walaupun posisi berubah
            $table->string('work_unit_id')->nullable();
            $table->foreign('work_unit_id')->references('id')->on('work_units')->onDelete('set null');
            $table->date('request_date')->nullable();
            $table->char('period', 7)->index(); // Format: "2025-04"
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'realized', 'completed', 'canceled'])->default('draft');
        
            // Tracking siapa yang buat, approve dan reject
            $table->string('created_by'); // user->employee_id
            $table->string('approved_by')->nullable(); // user->employee_id
            $table->string('rejected_by')->nullable(); // user->employee_id
            $table->string('realized_by')->nullable(); // user->employee_id
            $table->string('completed_by')->nullable(); // user->employee_id
            $table->text('request_note')->nullable();
            $table->text('approval_note')->nullable();
            $table->text('canceled_reason')->nullable();
        
            // Tanda terima
            $table->string('receipt_file')->nullable();

            // Tambahan kolom log
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('realized_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('printed_at')->nullable();
        
            $table->timestamps();
            $table->index(['period', 'status']);
            $table->index('employee_id');
            $table->index('created_by');
            $table->index('approved_by');
            $table->index('rejected_by');
            $table->index('realized_by');
            $table->index('completed_by');
        });

        Schema::create('atk_out_request_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('atk_out_request_id');
            $table->foreign('atk_out_request_id')->references('id')->on('atk_out_requests')->onDelete('cascade');
            $table->foreignId('atk_item_id')->constrained('atk_items')->onDelete('restrict');
            $table->integer('qty')->default(0);
            $table->integer('current_stock_at_request')->default(0);
            $table->integer('qty_approved')->nullable();
            $table->integer('qty_realized')->nullable();
            $table->timestamps();
            $table->index('atk_item_id');
        });

        Schema::create('atk_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_item_id')->constrained('atk_items');
            $table->string('work_unit_id')->nullable();
            $table->foreign('work_unit_id')->references('id')->on('work_units')->onDelete('set null');
            $table->integer('qty_returned')->default(0);
            $table->date('date');
            $table->string('reason')->nullable();
            $table->string('uploaded_by'); //employee_id
            $table->timestamps();
        });

        Schema::create('atk_stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('note');
            $table->string('adjusted_by'); //employee_id
            $table->timestamps();
        });

        Schema::create('atk_stock_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_stock_adjustment_id')->constrained()->onDelete('cascade');
            $table->foreignId('atk_item_id')->constrained('atk_items');
            $table->integer('adjustment_qty'); // bisa negatif
            $table->enum('reason_type', ['correction', 'loss', 'expired', 'others'])->default('correction');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atk_stock_adjustment_items');
        Schema::dropIfExists('atk_stock_adjustments');
        Schema::dropIfExists('atk_returns');
        Schema::dropIfExists('atk_out_request_items');
        Schema::dropIfExists('atk_out_requests');
        Schema::dropIfExists('atk_receive_items');
        Schema::dropIfExists('atk_receives');
        Schema::dropIfExists('atk_purchase_order_items');
        Schema::dropIfExists('atk_purchase_orders');
        Schema::dropIfExists('atk_stocks');
        Schema::dropIfExists('atk_items');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('users');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('work_units');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('divisions');
        Schema::dropIfExists('positions');
    }
};