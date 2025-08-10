<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use App\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Mantido para compatibilidade com versão anterior
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relação muitos-para-muitos com Roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Verifica se o usuário tem uma role específica
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if ($role instanceof Role) {
            return $this->roles->contains('id', $role->id);
        }

        return false;
    }

    /**
     * Verifica se o usuário é administrador (compatibilidade com versão anterior)
     */
    public function isAdmin(): bool
    {
        // Primeiro verifica no novo sistema de roles
        if ($this->hasRole('admin')) {
            return true;
        }
        
        // Fallback para o sistema antigo (campo role)
        return $this->role === 'admin';
    }

    /**
     * Atribui uma role ao usuário
     */
    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = Role::firstOrCreate(['name' => $role]);
        }

        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove uma role do usuário
     */
    public function removeRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role instanceof Role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Escopo para filtrar usuários por role
     */
    public function scopeRole($query, $role)
    {
        return $query->whereHas('roles', function($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * Relação com livros através da tabela borrowings
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'borrowings')
                    ->withPivot('id', 'borrowed_at', 'returned_at')
                    ->withTimestamps();
    }

    /**
     * Relação com os empréstimos
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}