<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:authors|max:255',
        ]);

        Author::create($request->all());

        return redirect()->route('authors.index')->with('success', 'Autor criado com sucesso.');
    }

    public function show(string $id)
    {
        $author = Author::findOrFail($id);
        return view('authors.show', compact('author'));
    }

    public function edit(string $id)
    {
        $author = Author::findOrFail($id);
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, string $id)
    {
        $author = Author::findOrFail($id);
    
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('authors')->ignore($author->id),
            ],
        ]);
    
        $author->update($request->all());
    
        return redirect()->route('authors.index')->with('success', 'Autor atualizado com sucesso.');
    }

    public function destroy(string $id)
    {
        $author = Author::findOrFail($id);
        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Autor excluído com sucesso.');
    }
}
