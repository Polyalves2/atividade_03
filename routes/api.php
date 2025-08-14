<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\User;

Route::middleware('auth:api')->group(function () {
    // Rota de verificação do usuário
    Route::get('/users/{id}/verify', function ($id) {
        try {
            $user = User::with('roles')->findOrFail($id);
            
            return response()->json([
                'matches' => true,
                'database_data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleNames()->first(),
                    'updated_at' => $user->updated_at->toDateTimeString()
                ],
                'server_timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'matches' => false,
                'error' => 'Usuário não encontrado no banco de dados'
            ], 404);
        }
    });
});