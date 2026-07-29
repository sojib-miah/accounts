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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('receipt_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('transaction_type', ['Opening', 'Purchase', 'Sale', 'Adjustment', 'Purchase Return', 'Sales Return']);
            $table->decimal('stock_in', 15, 2)->default(0);
            $table->decimal('stock_out', 15, 2)->default(0);
            $table->decimal('balance', 15, 2);
            $table->date('transaction_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
