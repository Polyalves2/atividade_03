<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Livro;
use App\Models\User;

class Emprestimo extends Model
{
    /**
     * Relacionamento com o modelo Livro
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    /**
     * Relacionamento com o modelo User
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'livro_id',
        'data_emprestimo',
        'data_devolucao_prevista',
        'data_devolucao_real',
        'status'
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'data_emprestimo' => 'datetime',
        'data_devolucao_prevista' => 'datetime',
        'data_devolucao_real' => 'datetime',
    ];
}