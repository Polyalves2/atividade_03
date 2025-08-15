<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Http\Request;

class EmprestimoController extends Controller
{
    public function index()
    {
        $emprestimos = Emprestimo::with(['livro', 'user'])->get();
        return view('emprestimos.index', compact('emprestimos'));
    }

    public function create()
    {
        $livros = Livro::all();
        $users = User::all();
        return view('emprestimos.create', compact('livros', 'users'));
    }

    public function store(Request $request)
    {
        // Validação básica
        $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Funcionalidade 9: Verificar se livro já está emprestado
        $livroJaEmprestado = Emprestimo::where('livro_id', $request->livro_id)
            ->whereNull('data_devolucao')
            ->exists();

        if ($livroJaEmprestado) {
            return back()->with('error', 'Este livro já está emprestado e não pode ser emprestado novamente.');
        }

        // Criar o empréstimo
        Emprestimo::create([
            'livro_id' => $request->livro_id,
            'user_id' => $request->user_id,
            'data_emprestimo' => now(),
        ]);

        return redirect()->route('emprestimos.index')
            ->with('success', 'Empréstimo realizado com sucesso!');
    }

    public function devolver($id)
    {
        $emprestimo = Emprestimo::findOrFail($id);
        $emprestimo->data_devolucao = now();
        $emprestimo->save();

        return back()->with('success', 'Livro devolvido com sucesso!');
    }
}