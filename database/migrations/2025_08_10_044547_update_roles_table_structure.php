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
        // Verifica e adiciona colunas faltantes
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'name')) {
                $table->string('name')->unique()->after('id');
            }
            if (!Schema::hasColumn('roles', 'description')) {
                $table->string('description')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
