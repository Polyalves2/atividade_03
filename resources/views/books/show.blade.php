<!-- resources/views/books/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-4 bg-light p-3 rounded text-center">
                <img src="{{ $book->image_url }}" class="img-fluid rounded" alt="{{ $book->title }}" style="max-height: 500px; width: auto;">
            </div>
        </div>
        
        <div class="col-md-6">
            <h1 class="mb-3">{{ $book->title }}</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-subtitle mb-3 text-muted">
                        {{ $book->author->name }} • {{ $book->published_year }}
                    </h5>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Editora:</strong> {{ $book->publisher->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Categoria:</strong> {{ $book->category->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>ISBN:</strong> {{ $book->isbn ?? 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                @auth
                <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection