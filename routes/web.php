<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('categories', CategoryController::class);
Route::resource('authors', AuthorController::class);
Route::resource('publishers', PublisherController::class);

// Rotas para criação de livros (unificadas e padronizadas)
Route::prefix('books')->group(function () {
    Route::get('/create-id', [BookController::class, 'createWithId'])->name('books.create.id');
    Route::post('/create-id', [BookController::class, 'storeWithId'])->name('books.store.id');

    Route::get('/create-select', [BookController::class, 'createWithSelect'])->name('books.create.select');
    Route::post('/create-select', [BookController::class, 'storeWithSelect'])->name('books.store.select');
});

// Rotas RESTful padrão para livros (exceto create/store customizados)
Route::resource('books', BookController::class)->except(['create', 'store']);

// Resource completo para usuários
Route::resource('users', UserController::class);

// Rota extra para atualização individual de role via select (AJAX)
Route::patch('/users/{id}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');

// Rotas de empréstimos
Route::post('/books/{book}/borrow', [BorrowingController::class, 'store'])->name('books.borrow');
Route::get('/users/{user}/borrowings', [BorrowingController::class, 'userBorrowings'])->name('users.borrowings');
Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');

// Rotas administrativas protegidas — somente admin autenticado
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users/roles', [UserController::class, 'showRoleEditor'])->name('admin.roles');
    Route::patch('/users/update-roles', [UserController::class, 'updateRoles'])->name('users.updateRoles');
});

// Rotas para empréstimos em português
Route::resource('emprestimos', EmprestimoController::class)->only([
    'index', 'create', 'store'
]);

Route::post('emprestimos/{emprestimo}/devolver', [EmprestimoController::class, 'devolver'])
    ->name('emprestimos.devolver');

Route::post('emprestimos/{emprestimo}/devolver', [EmprestimoController::class, 'devolver'])
    ->name('emprestimos.devolver');
    