<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. stock_atk
        Schema::create('stock_atk', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit');
            $table->integer('stock_qty')->default(0);
            $table->integer('min_stock')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. po_atk
        Schema::create('po_atk', function (Blueprint $table) {
            $table->id();
            $table->string('po_number');
            $table->date('po_date');
            $table->uuid('created_by');
            $table->timestamps();
        });

        // 3. po_atk_items
        Schema::create('po_atk_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_atk_id')->constrained('po_atk')->onDelete('cascade');
            $table->foreignId('atk_id')->constrained('stock_atk');
            $table->integer('quantity');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        // 4. receive_atk
        Schema::create('receive_atk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_atk_id')->constrained('po_atk');
            $table->uuid('received_by');
            $table->date('delivery_date');
            $table->string('status')->default('partial');
            $table->timestamps();
        });

        // 5. receive_atk_items
        Schema::create('receive_atk_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receive_atk_id')->constrained('receive_atk')->onDelete('cascade');
            $table->foreignId('atk_id')->constrained('stock_atk');
            $table->integer('received_qty');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        // 6. atk_out_requests
        Schema::create('atk_out_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('requested_by');
            $table->uuid('created_by');
            $table->uuid('approved_by')->nullable();
            $table->date('request_date');
            $table->string('status')->default('draft');
            $table->string('receipt_file')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->string('period')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_hash')->nullable();
            $table->timestamps();
        });

        // 7. atk_out_items
        Schema::create('atk_out_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_out_request_id')->constrained('atk_out_requests')->onDelete('cascade');
            $table->foreignId('atk_id')->constrained('stock_atk');
            $table->integer('quantity');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        // 8. atk_returns
        Schema::create('atk_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_unit_id')->constrained('work_units')->onDelete('cascade');
            $table->foreignId('atk_id')->constrained('stock_atk')->onDelete('cascade');
            $table->integer('qty_returned');
            $table->date('date');
            $table->text('reason');
            $table->uuid('uploaded_by');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
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
    }
};
