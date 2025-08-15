<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanTablesSeeder extends Seeder
{
    public function run(): void
    {
        // Desativa checagem de foreign keys
        DB::statement('PRAGMA foreign_keys = OFF;'); // Para SQLite
        // Se fosse MySQL, usar: DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'categories',
            'authors',
            'publishers',
            'books',
            'borrowings',
            // adicione outras tabelas se existirem
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // Reativa checagem de foreign keys
        DB::statement('PRAGMA foreign_keys = ON;'); // Para SQLite
        // Se fosse MySQL, usar: DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Tabelas limpas com sucesso!');
    }
}
