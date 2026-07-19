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
        Schema::create('company_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete()->nullOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('expire_date')->nullable();
            $table->enum('status', ['Active', 'Expired', 'Cancelled'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_packages');
    }
};
