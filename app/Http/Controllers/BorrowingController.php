<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Registra um novo empréstimo de livro
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Book $book)
    {
        // Valida o usuário
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->user_id;
        $user = User::findOrFail($userId);

        // Verifica se o livro já está emprestado
        $openBorrowing = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->first();

        if ($openBorrowing) {
            return redirect()->back()->with('error', 'Este livro já está emprestado.');
        }

        // Verifica se o usuário possui débito pendente
        if ($user->debit > 0) {
            return redirect()->back()->with('error', 'O usuário possui débitos pendentes e não pode realizar empréstimos.');
        }

        // Limite de 5 livros ativos por usuário
        $activeBorrowings = Borrowing::where('user_id', $userId)
            ->whereNull('returned_at')
            ->count();

        if ($activeBorrowings >= 5) {
            return redirect()->back()->with('error', 'O usuário já possui 5 livros emprestados.');
        }

        // Cria o empréstimo dentro de uma transação
        DB::transaction(function () use ($userId, $book) {
            Borrowing::create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'expected_return_date' => now()->addDays(15),
                'returned_at' => null,
            ]);

            // Atualiza o status do livro
            $book->update(['available' => false]);
        });

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Empréstimo realizado com sucesso.');
    }

    /**
     * Registra a devolução de um livro
     *
     * @param  \App\Models\Borrowing  $borrowing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnBook(Borrowing $borrowing)
    {
        DB::transaction(function () use ($borrowing) {
            // Atualiza a devolução
            $borrowing->update([
                'returned_at' => now(),
            ]);

            // Atualiza o status do livro
            $borrowing->book->update(['available' => true]);

            // Calcula multa se houver atraso
            if (Carbon::parse($borrowing->expected_return_date)->lt(now())) {
                $daysLate = Carbon::parse($borrowing->expected_return_date)->diffInDays(now());
                $fine = $daysLate * 0.5;

                // Acrescenta multa ao débito do usuário
                $borrowing->user->increment('debit', $fine);
            }
        });

        $returnMessage = 'Devolução registrada com sucesso.';
        if (Carbon::parse($borrowing->expected_return_date)->lt(now())) {
            $daysLate = Carbon::parse($borrowing->expected_return_date)->diffInDays(now());
            $returnMessage .= " (Devolução atrasada em {$daysLate} dias)";
        }

        return redirect()
            ->route('books.show', $borrowing->book_id)
            ->with('success', $returnMessage);
    }

    /**
     * Exibe todos os empréstimos de um usuário
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function userBorrowings(User $user)
    {
        $borrowings = $user->borrowings()
            ->with('book')
            ->orderByDesc('borrowed_at')
            ->paginate(10);

        return view('users.borrowings', compact('user', 'borrowings'));
    }

    /**
     * Exibe todos os empréstimos ativos
     *
     * @return \Illuminate\View\View
     */
    public function activeBorrowings()
    {
        $borrowings = Borrowing::with(['user', 'book'])
            ->whereNull('returned_at')
            ->orderBy('expected_return_date')
            ->paginate(15);

        return view('borrowings.active', compact('borrowings'));
    }

    /**
     * Exibe empréstimos atrasados
     *
     * @return \Illuminate\View\View
     */
    public function overdueBorrowings()
    {
        $borrowings = Borrowing::with(['user', 'book'])
            ->whereNull('returned_at')
            ->where('expected_return_date', '<', now())
            ->orderBy('expected_return_date')
            ->paginate(15);

        return view('borrowings.overdue', compact('borrowings'));
    }

    /**
     * Zera o débito de um usuário (somente para bibliotecário)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearDebit(User $user)
    {
        $user->update(['debit' => 0]);
        return redirect()->back()->with('success', 'Débito zerado com sucesso.');
    }
}
