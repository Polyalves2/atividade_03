<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Exibe o formulário de criação com input de ID
     *
     * @return \Illuminate\View\View
     */
    public function createWithId()
    {
        return view('books.create-id');
    }

    /**
     * Armazena um novo livro usando input de ID
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWithId(Request $request)
    {
        $validated = $this->validateBookRequest($request);
        $data = $this->handleImageUpload($request);

        Book::create($data);

        return redirect()->route('books.index')
               ->with('success', 'Livro criado com sucesso.');
    }

    /**
     * Exibe o formulário de criação com selects
     *
     * @return \Illuminate\View\View
     */
    public function createWithSelect()
    {
        return view('books.create-select', [
            'publishers' => Publisher::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get()
        ]);
    }

    /**
     * Armazena um novo livro usando selects
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWithSelect(Request $request)
    {
        $validated = $this->validateBookRequest($request);
        $data = $this->handleImageUpload($request);

        Book::create($data);

        return redirect()->route('books.index')
               ->with('success', 'Livro criado com sucesso.');
    }

    /**
     * Exibe o formulário de edição
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\View\View
     */
    public function edit(Book $book)
    {
        return view('books.edit', [
            'book' => $book,
            'publishers' => Publisher::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get()
        ]);
    }
    
    /**
     * Atualiza um livro existente
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Book $book)
    {
        $validated = $this->validateBookRequest($request);
        $data = $this->handleImageUpload($request, $book);

        $book->update($data);

        return redirect()->route('books.index')
               ->with('success', 'Livro atualizado com sucesso.');
    }
    
    /**
     * Exibe os detalhes de um livro
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\View\View
     */
    public function show(Book $book)
    {
        return view('books.show', [
            'book' => $book->load(['author', 'publisher', 'category']),
            'users' => User::orderBy('name')->get()
        ]);
    }
    
    /**
     * Lista todos os livros
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('books.index', [
            'books' => Book::with(['author', 'publisher', 'category'])
                          ->orderBy('title')
                          ->paginate(20)
        ]);
    }

    /**
     * Remove um livro
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        
        // Remove a imagem associada se existir
        if ($book->image_path) {
            Storage::disk('public')->delete($book->image_path);
        }
        
        $book->delete();
    
        return redirect()->route('books.index')
               ->with('success', 'Livro deletado com sucesso!');
    }

    /**
     * Validação centralizada para livros
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function validateBookRequest(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'edition' => 'nullable|string|max:50',
            'stock' => 'nullable|integer|min:0'
        ]);
    }

    /**
     * Manipulação centralizada do upload de imagens
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book|null  $book
     * @return array
     */
    protected function handleImageUpload(Request $request, Book $book = null)
    {
        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {
            // Remove a imagem antiga se existir
            if ($book && $book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }
            
            $data['image_path'] = $request->file('image_path')
                ->store('books', 'public');
        }

        return $data;
    }
}