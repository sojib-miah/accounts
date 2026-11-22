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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 15, 2)->default(0);
            $table->string('company_limit')->default('Unlimited');
            $table->string('branch_limit')->default('Unlimited');
            $table->string('user_limit')->default('Unlimited');
            $table->string('supplier_limit')->default('Unlimited');
            $table->string('customer_limit')->default('Unlimited');
            $table->string('payment_type_limit')->default('Unlimited');
            $table->string('account_limit')->default('Unlimited');
            $table->string('product_limit')->default('Unlimited');
            $table->string('sales_order_limit')->default('Unlimited');
            $table->integer('storage_limit')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
