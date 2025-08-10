<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Dados gerais para o dashboard
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('available', true)->count(),
            'active_borrowings' => Borrowing::whereNull('returned_at')->count(),
            'overdue_borrowings' => Borrowing::whereNull('returned_at')
                ->where('expected_return_date', '<', now())
                ->count(),
        ];

        // Dados específicos do usuário (se não for admin)
        $userBorrowings = [];
        if (!$user->isAdmin()) {
            $userBorrowings = $user->borrowings()
                ->with('book')
                ->whereNull('returned_at')
                ->orderBy('expected_return_date')
                ->take(5)
                ->get();
        }

        // Últimos livros adicionados
        $recentBooks = Book::with(['author', 'category'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Empréstimos recentes (apenas para admin)
        $recentBorrowings = [];
        if ($user->isAdmin()) {
            $recentBorrowings = Borrowing::with(['user', 'book'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('home', compact(
            'stats',
            'userBorrowings',
            'recentBooks',
            'recentBorrowings'
        ));
    }

    /**
     * Show the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = auth()->user();
        $borrowings = $user->borrowings()
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile', compact('user', 'borrowings'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return $this->redirectWithSuccess('home.profile', 'Perfil atualizado com sucesso!');
    }
}