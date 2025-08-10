<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'author_id', 
        'category_id', 
        'publisher_id', 
        'published_year',
        'image_path',
        'description'
    ];

    protected $appends = ['image_url'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'borrowings')
                    ->withPivot('id', 'borrowed_at', 'returned_at')
                    ->withTimestamps();
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function getImageUrlAttribute()
    {
        // Se não houver imagem definida, retorna imagem padrão
        if (!$this->image_path) {
            return asset('images/default-book.jpg') . '?v=' . time();
        }

        // Se for uma URL externa, retorna sem modificação
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        // Verifica se o arquivo existe no storage
        $fileExists = Storage::disk('public')->exists($this->image_path);

        // Se o arquivo existir, retorna com timestamp de modificação
        if ($fileExists) {
            $timestamp = Storage::disk('public')->lastModified($this->image_path);
            return asset('storage/' . $this->image_path) . '?v=' . $timestamp;
        }

        // Se o arquivo não existir, retorna imagem padrão
        return asset('images/default-book.jpg') . '?v=' . time();
    }
}