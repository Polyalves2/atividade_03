<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Exibe a lista de categorias
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::withCount('books')
            ->orderBy('name')
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Mostra o formulário de criação de categoria
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Armazena uma nova categoria no banco de dados
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
            ]);
        });

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria criada com sucesso.');
    }

    /**
     * Exibe os detalhes de uma categoria específica
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show(string $id)
    {
        $category = Category::with(['books' => function($query) {
            $query->orderBy('title')->paginate(10);
        }])->findOrFail($id);

        return view('categories.show', compact('category'));
    }

    /**
     * Mostra o formulário para edição de categoria
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Atualiza uma categoria no banco de dados
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id . '|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $category) {
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
            ]);
        });

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove uma categoria do banco de dados
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        // Verifica se a categoria possui livros associados
        if ($category->books()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Não é possível excluir a categoria pois existem livros associados a ela.');
        }

        DB::transaction(function () use ($category) {
            $category->delete();
        });

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria excluída com sucesso.');
    }
}