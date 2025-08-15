<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name', 'asc')->paginate(15);
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('users.show', compact('user', 'roles'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        if ($user->email === 'admin@biblioteca.com') {
            return redirect()->route('users.index')
                ->with('error', 'O Administrador Principal não pode ser editado.');
        }

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('roles')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|exists:roles,name'
        ]);

        DB::beginTransaction();

        try {
            // Atualização dos campos básicos
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->save();

            // Atualização da role
            $role = Role::where('name', $validated['role'])->first();
            $user->syncRoles([$role->id]);

            DB::commit();

            // Redireciona para a lista de usuários
            return redirect()->route('users.index')
                ->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    // Novo método para atualizar role via AJAX
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->email === 'admin@biblioteca.com') {
            return response()->json([
                'message' => 'O Administrador Principal não pode ter a role alterada.'
            ], 403);
        }

        $roleName = $request->role;
        if ($roleName) {
            $user->syncRoles([$roleName]);
        }

        return response()->json(['message' => 'Papel do usuário atualizado com sucesso.']);
    }

    // Método destroy ajustado
    public function destroy($id)
    {
        $user = User::findOrFail($id);
    
        // Bloqueia a exclusão do Administrador Principal por outros usuários
        if ($user->email === 'admin@biblioteca.com' && auth()->id() !== $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'O Administrador Principal não pode ser editado.');
        }
    
        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}    