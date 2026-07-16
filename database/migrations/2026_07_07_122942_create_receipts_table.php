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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique();
            $table->enum('type', [
                'Income',
                'Expense'
            ]);
            $table->foreignId('company_id')->nullable()
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('party_id')
                ->constrained('parties')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->date('receipt_date');
            $table->text('remarks')->nullable();
            $table->integer('total_qty')->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('vat', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('due_amount', 15, 2)->default(0);
            $table->enum('payment_status', [
                'Pending',
                'Partial',
                'Paid'
            ])->default('Pending');
            $table->enum('status', [
                'Draft',
                'Completed',
                'Cancelled'
            ])->default('Draft');
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
