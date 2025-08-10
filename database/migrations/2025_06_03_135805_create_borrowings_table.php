<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // public function up(): void
    public function up()
    {
        if (!Schema::hasTable('borrowings')) {
            Schema::create('borrowings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
                $table->foreignId('book_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
                $table->timestamp('borrowed_at')->useCurrent();
                $table->date('expected_return_date');
                $table->timestamp('returned_at')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'book_id', 'returned_at']);
            });
        } else {
            // Adiciona apenas colunas faltantes
            Schema::table('borrowings', function (Blueprint $table) {
                if (!Schema::hasColumn('borrowings', 'expected_return_date')) {
                    $table->date('expected_return_date')->after('borrowed_at');
                }
                // Adicione verificações para outras colunas se necessário
            });
        }
    }

    
    // public function down(): void
    public function down()
    {
        // Remove as chaves estrangeiras primeiro
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['book_id']);
        });
        
        Schema::dropIfExists('borrowings');
    }
};
