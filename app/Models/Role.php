<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * Os atributos que são mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Relacionamento muitos-para-muitos com User
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                   ->withTimestamps()
                   ->withPivot([]); // Remove se quiser usar timestamps na pivot
    }

    /**
     * Escopo para filtrar por nome de role
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Verifica se a role tem usuários associados
     */
    public function hasUsers(): bool
    {
        return $this->users()->exists();
    }
}