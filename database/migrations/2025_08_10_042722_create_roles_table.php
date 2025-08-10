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
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        } else {
            // Adiciona colunas faltantes se a tabela já existe
            Schema::table('roles', function (Blueprint $table) {
                if (!Schema::hasColumn('roles', 'description')) {
                    $table->string('description')->nullable()->after('name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
