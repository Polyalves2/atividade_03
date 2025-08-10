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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'expected_return_date' => 'required|date|after_or_equal:today',
        ]);

        // Verifica se o livro já está emprestado e não foi devolvido
        $activeBorrowing = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if ($activeBorrowing) {
            return back()->with('error', 'Este livro já está emprestado e não foi devolvido.');
        }

        DB::transaction(function () use ($request, $book) {
            Borrowing::create([
                'user_id' => $request->user_id,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'expected_return_date' => $request->expected_return_date,
            ]);

            // Atualiza o status do livro se necessário
            $book->update(['available' => false]);
        });

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Empréstimo registrado com sucesso.');
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
            $borrowing->update([
                'returned_at' => now(),
            ]);

            // Atualiza o status do livro
            $borrowing->book->update(['available' => true]);
        });

        $returnMessage = 'Devolução registrada com sucesso.';

        // Verifica se houve atraso
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
}