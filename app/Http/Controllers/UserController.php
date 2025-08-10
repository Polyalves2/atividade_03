<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->has('role')) {
            $query->role($request->input('role'));
        }

        $users = $query->orderBy('name')
                      ->paginate(15)
                      ->withQueryString();

        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $borrowings = $user->borrowings()
            ->with('book')
            ->orderByDesc('borrowed_at')
            ->paginate(10);

        return view('users.show', compact('user', 'borrowings'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $user) {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Sync roles if provided
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }
        });

        return $this->redirectWithSuccess(
            'users.show', 
            'Usuário atualizado com sucesso.',
            $user
        );
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'current_password' => 'required_with:password|string|current_password',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $user) {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);
        });

        return $this->redirectWithSuccess(
            'users.edit-profile', 
            'Perfil atualizado com sucesso.'
        );
    }

    public function deactivate(User $user)
    {
        if ($user->is(auth()->user())) {
            return $this->redirectWithError(
                'users.index',
                'Você não pode desativar sua própria conta.'
            );
        }

        DB::transaction(function () use ($user) {
            $user->delete();
        });

        return $this->redirectWithSuccess(
            'users.index', 
            'Usuário desativado com sucesso.'
        );
    }
}