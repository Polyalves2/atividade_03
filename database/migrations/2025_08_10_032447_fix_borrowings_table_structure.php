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
        Schema::table('borrowings', function (Blueprint $table) {
            if (!Schema::hasColumn('borrowings', 'expected_return_date')) {
                $table->date('expected_return_date')->after('borrowed_at');
            }
            if (!Schema::hasColumn('borrowings', 'returned_at')) {
                $table->timestamp('returned_at')->nullable()->after('expected_return_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não reverte as alterações para evitar problemas
    }
};
