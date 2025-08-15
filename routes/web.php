<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\EmprestimoController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Resource Routes
Route::resource('categories', CategoryController::class);
Route::resource('authors', AuthorController::class);
Route::resource('publishers', PublisherController::class);
Route::resource('users', UserController::class);

// Book Routes
Route::prefix('books')->group(function () {
    Route::get('/create-id', [BookController::class, 'createWithId'])->name('books.create.id');
    Route::post('/create-id', [BookController::class, 'storeWithId'])->name('books.store.id');
    Route::get('/create-select', [BookController::class, 'createWithSelect'])->name('books.create.select');
    Route::post('/create-select', [BookController::class, 'storeWithSelect'])->name('books.store.select');
});
Route::resource('books', BookController::class)->except(['create', 'store']);

// User Role Management
Route::patch('/users/{id}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');

// Borrowing Routes (English)
Route::post('/books/{book}/borrow', [BorrowingController::class, 'store'])->name('books.borrow');
Route::get('/users/{user}/borrowings', [BorrowingController::class, 'userBorrowings'])->name('users.borrowings');
Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])
    ->name('borrowings.return');

// Empréstimo Routes (Portuguese)
Route::resource('emprestimos', EmprestimoController::class)->only(['index', 'create', 'store']);
Route::post('emprestimos/{emprestimo}/devolver', [EmprestimoController::class, 'devolver'])
    ->name('emprestimos.devolver');

// Admin Protected Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users/roles', [UserController::class, 'showRoleEditor'])->name('admin.roles');
    Route::patch('/users/update-roles', [UserController::class, 'updateRoles'])->name('users.updateRoles');
});