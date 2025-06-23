<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // public function up(): void
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('author_id')->onDelete('cascade');
            $table->foreignId('category_id')->onDelete('cascade');
            $table->foreignId('publisher_id')->onDelete('cascade');
            $table->integer('published_year')->nullable();
            $table->timestamps();
        });
    }

    // public function down(): void
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
