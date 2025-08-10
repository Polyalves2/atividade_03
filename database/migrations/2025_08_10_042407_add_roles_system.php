<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cria tabela de roles se não existir
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }
    
        // Cria tabela pivot se não existir
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('role_id')->constrained()->cascadeOnDelete();
                $table->primary(['user_id', 'role_id']);
            });
        }
    
        // Migra os roles antigos (opcional)
        if (Schema::hasColumn('users', 'role')) {
            $adminUsers = DB::table('users')->where('role', 'admin')->get();
            
            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'description' => 'Administrador do sistema'
            ]);
    
            foreach ($adminUsers as $user) {
                DB::table('role_user')->insertOrIgnore([
                    'user_id' => $user->id,
                    'role_id' => $adminRole->id
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
