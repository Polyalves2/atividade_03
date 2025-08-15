<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona as colunas status e notes à tabela borrowings
     */
    public function up()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->string('status')->default('borrowed')->after('returned_at');
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Remove as colunas adicionadas
     */
    public function down()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['status', 'notes']);
        });
    }
};