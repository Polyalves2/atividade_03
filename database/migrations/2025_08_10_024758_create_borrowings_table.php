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
        if (!Schema::hasTable('borrowings')) {
            Schema::create('borrowings', function (Blueprint $table) {
                // Sua definição de tabela original aqui
            });
        } else {
            // Adiciona apenas colunas faltantes
            Schema::table('borrowings', function (Blueprint $table) {
                if (!Schema::hasColumn('borrowings', 'expected_return_date')) {
                    $table->date('expected_return_date')->after('borrowed_at');
                }
            });
        }
    }

    public function down(): void
    {

    }
};