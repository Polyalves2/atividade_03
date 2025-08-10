@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Lista de Livros</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between mb-4">
        <div>
            <a href="{{ route('books.create.id') }}" class="btn btn-success">
                <i class="bi bi-plus"></i> Adicionar (Com ID)
            </a>
            <a href="{{ route('books.create.select') }}" class="btn btn-primary ms-2">
                <i class="bi bi-plus"></i> Adicionar (Com Select)
            </a>
        </div>
    </div>

    <div class="row">
        @forelse($books as $book)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($book->image_path)
                        <img src="{{ asset('storage/' . $book->image_path) }}" 
                             class="card-img-top" 
                             alt="{{ $book->title }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="text-center py-5 bg-light">
                            <i class="bi bi-book" style="font-size: 3rem; color: #6c757d;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text text-muted">{{ $book->author->name }}</p>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir este livro?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Nenhum livro encontrado.</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
</div>
@endsection