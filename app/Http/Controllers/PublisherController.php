<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::all();
        return view('publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:publishers|max:255',
        ]);

        Publisher::create($request->all());

        return redirect()->route('publishers.index')->with('success', 'Editora criada com sucesso.');
    }

    public function show(string $id)
    {
        $publisher = Publisher::findOrFail($id);
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

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('publishers')->ignore($publisher->id),
            ],
        ]);

        $publisher->update($request->all());

        return redirect()->route('publishers.index')->with('success', 'Editora atualizada com sucesso.');
    }

    public function destroy(string $id)
    {
        $publisher = Publisher::findOrFail($id);
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Editora excluída com sucesso.');
    }
}
