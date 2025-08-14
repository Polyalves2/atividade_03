@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Detalhes do Livro</h1>
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Voltar para lista
        </a>
    </div>

    <div class="row g-4">
        <!-- Coluna da Capa -->
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body p-3 text-center">
                    @if(!empty($book->image_url))
                        <img src="{{ $book->image_url }}" 
                             class="img-fluid rounded" 
                             alt="Capa de {{ $book->title }}"
                             style="max-height: 400px; width: auto;">
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center p-5 bg-light rounded">
                            <i class="bi bi-book text-muted" style="font-size: 5rem;"></i>
                            <span class="text-muted mt-2">Capa não disponível</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coluna dos Detalhes -->
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h2 class="h5 mb-0">{{ $book->title ?? 'Título não informado' }}</h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h3 class="h5 mb-3 text-primary">Informações Básicas</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong class="d-block text-muted small">Autor</strong>
                                @if($book->author)
                                    <a href="{{ route('authors.show', $book->author->id) }}" class="text-decoration-none">
                                        {{ $book->author?->name ?? 'Não informado' }}
                                    </a>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </li>
                            <li class="mb-2">
                                <strong class="d-block text-muted small">Editora</strong>
                                @if($book->publisher)
                                    <a href="{{ route('publishers.show', $book->publisher->id) }}" class="text-decoration-none">
                                        {{ $book->publisher?->name ?? 'Não informado' }}
                                    </a>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </li>
                            <li class="mb-2">
                                <strong class="d-block text-muted small">Categoria</strong>
                                @if($book->category)
                                    <a href="{{ route('categories.show', $book->category->id) }}" class="text-decoration-none">
                                        {{ $book->category?->name ?? 'Não informado' }}
                                    </a>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </li>
                            @if(!empty($book->published_year))
                            <li class="mb-2">
                                <strong class="d-block text-muted small">Ano de Publicação</strong>
                                <span>{{ $book->published_year }}</span>
                            </li>
                            @endif
                            @if(!empty($book->isbn))
                            <li class="mb-2">
                                <strong class="d-block text-muted small">ISBN</strong>
                                <span>{{ $book->isbn }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>

                    @if(!empty($book->description))
                    <div class="mb-4">
                        <h3 class="h5 mb-3 text-primary">Sinopse</h3>
                        <p class="text-muted">{{ $book->description }}</p>
                    </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning px-3">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-3" 
                                    onclick="return confirm('Tem certeza que deseja excluir este livro permanentemente?')">
                                <i class="bi bi-trash me-1"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
