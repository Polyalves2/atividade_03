<?php

namespace App\Http\Controllers;

use App\Models\Borrowing; // Usando o modelo Borrowing (Empréstimo)
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Registra a devolução de um empréstimo
     */
    public function returnBook($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        
        // Verifica se já foi devolvido
        if ($borrowing->returned_at) {
            return redirect()->back()
                ->with('error', 'Este livro já foi devolvido.');
        }

        // Atualiza a devolução
        $borrowing->update([
            'returned_at' => now(),
            'status' => 'returned'
        ]);

        // Verifica atraso
        $expectedReturn = Carbon::parse($borrowing->borrowed_at)->addDays(15);
        $daysLate = now()->diffInDays($expectedReturn, false);

        // Aplica multa se houver atraso
        if ($daysLate < 0) {
            $fine = abs($daysLate) * 0.5; // R$ 0,50 por dia de atraso
            
            User::where('id', $borrowing->user_id)
                ->increment('debit', $fine);
            
            return redirect()->back()
                ->with('warning', "Livro devolvido com atraso de ".abs($daysLate)." dias. Multa de R$ ".number_format($fine, 2)." aplicada.");
        }

        return redirect()->back()
            ->with('success', 'Livro devolvido com sucesso.');
    }
}