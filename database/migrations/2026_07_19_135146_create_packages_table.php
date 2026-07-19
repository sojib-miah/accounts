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
            $table->integer('user_limit')->default(0);
            $table->integer('company_limit')->default(0);
            $table->integer('income_limit')->default(0);
            $table->integer('expense_limit')->default(0);
            $table->integer('challan_limit')->default(0);
            $table->integer('branch_limit')->default(0);
            $table->integer('party_limit')->default(0);
            $table->integer('account_limit')->default(0);
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
