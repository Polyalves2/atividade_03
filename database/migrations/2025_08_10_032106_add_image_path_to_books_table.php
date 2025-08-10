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
        if (!Schema::hasColumn('books', 'image_path')) {
            Schema::table('books', function (Blueprint $table) {
                $table->string('image_path')->nullable()->after('published_year');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Opcional - não necessário se for produção
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
