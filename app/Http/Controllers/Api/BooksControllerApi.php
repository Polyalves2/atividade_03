<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksControllerApi extends Controller
{
    public function index()
    {
        return Book::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'available' => 'required|boolean',
        ]);

        $book = Book::create($validated);

        return response()->json($book, 201);
    }

    public function show(Book $book)
    {
        return $book;
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'available' => 'required|boolean',
        ]);

        $book->update($validated);

        return response()->json($book);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(null, 204);
    }
}
