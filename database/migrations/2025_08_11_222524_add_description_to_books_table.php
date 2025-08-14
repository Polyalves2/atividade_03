<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToBooksTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('books', 'description')) {
            Schema::table('books', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('books', 'description')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
}
