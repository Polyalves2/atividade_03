<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::query()->withCount('books');
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('founding_year', 'like', "%{$search}%");
        }

        $publishers = $query->orderBy('name')
                          ->paginate(15)
                          ->withQueryString();

        return view('publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('publishers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:publishers|max:255',
            'founding_year' => 'nullable|integer|min:1500|max:' . date('Y'),
            'headquarters' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            Publisher::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'founding_year' => $request->founding_year,
                'headquarters' => $request->headquarters,
                'website' => $request->website,
                'description' => $request->description,
            ]);
        });

        return $this->redirectWithSuccess(
            'publishers.index', 
            'Editora criada com sucesso.'
        );
    }

    public function show(string $id)
    {
        $publisher = Publisher::with(['books' => function($query) {
            $query->orderBy('title')->paginate(10);
        }])->findOrFail($id);

        return view('publishers.show', compact('publisher'));
    }

    public function edit(string $id)
    {
        $publisher = Publisher::findOrFail($id);
        return view('publishers.edit', compact('publisher'));
    }

    public function update(Request $request, string $id)
    {
        $publisher = Publisher::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('publishers')->ignore($publisher->id),
            ],
            'founding_year' => 'nullable|integer|min:1500|max:' . date('Y'),
            'headquarters' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $publisher) {
            $publisher->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'founding_year' => $request->founding_year,
                'headquarters' => $request->headquarters,
                'website' => $request->website,
                'description' => $request->description,
            ]);
        });

        return $this->redirectWithSuccess(
            'publishers.index', 
            'Editora atualizada com sucesso.'
        );
    }

    public function destroy(string $id)
    {
        $publisher = Publisher::findOrFail($id);

        // Check if publisher has associated books
        if ($publisher->books()->exists()) {
            return $this->redirectWithError(
                'publishers.index',
                'Não é possível excluir a editora pois existem livros associados a ela.'
            );
        }

        DB::transaction(function () use ($publisher) {
            $publisher->delete();
        });

        return $this->redirectWithSuccess(
            'publishers.index', 
            'Editora excluída com sucesso.'
        );
    }
}