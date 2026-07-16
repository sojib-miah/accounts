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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('name');
            $table->string('hologram')->nullable()->after('logo');
            $table->string('seal')->nullable()->after('hologram');
            $table->string('signature')->nullable()->after('seal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'logo',
                'hologram',
                'seal',
                'signature'
            ]);
        });
    }
};
