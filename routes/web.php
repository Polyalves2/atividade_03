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
    // Rota com input de ID
    Route::get('/create-id', [BookController::class, 'createWithId'])->name('books.create.id');
    Route::post('/create-id', [BookController::class, 'storeWithId'])->name('books.store.id');
    
    // Rota com select
    Route::get('/create-select', [BookController::class, 'createWithSelect'])->name('books.create.select');
    Route::post('/create-select', [BookController::class, 'storeWithSelect'])->name('books.store.select');
});

// Rotas RESTful padrão (excluindo create/store que já temos versões customizadas)
Route::resource('books', BookController::class)->except(['create', 'store']);

Route::resource('users', UserController::class)->except(['create', 'store', 'destroy']);

// Rotas de empréstimos
Route::post('/books/{book}/borrow', [BorrowingController::class, 'store'])->name('books.borrow');
Route::get('/users/{user}/borrowings', [BorrowingController::class, 'userBorrowings'])->name('users.borrowings');
Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');