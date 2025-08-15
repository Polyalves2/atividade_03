<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livro extends Model
{
    /**
     * Relacionamento com o modelo Emprestimo (um livro pode ter muitos empréstimos)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emprestimos(): HasMany
    {
        return $this->hasMany(Emprestimo::class);
    }

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'titulo',
        'isbn',
        'ano_publicacao',
        'edicao',
        'quantidade',
        'categoria_id',
        'autor_id',
        'editora_id'
        // Adicione outros campos conforme necessário
    ];

    /**
     * Relacionamento com a categoria
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relacionamento com o autor
     */
    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    /**
     * Relacionamento com a editora
     */
    public function editora()
    {
        return $this->belongsTo(Editora::class);
    }
}