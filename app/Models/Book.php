<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'author_id', 
        'category_id', 
        'publisher_id', 
        'published_year',
        'image_path'
    ];

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
        if (!$this->image_path) {
            return asset('images/default-book.jpg');
        }
        
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        
        return asset('storage/' . $this->image_path);
    }
}