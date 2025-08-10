@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Editar Livro</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                @if($book->image_path)
                    <img src="{{ asset('storage/' . $book->image_path) }}" 
                         class="card-img-top" 
                         alt="Capa atual do livro"
                         style="max-height: 300px; object-fit: contain;">
                @else
                    <div class="text-center py-5 bg-light">
                        <i class="bi bi-book" style="font-size: 3rem; color: #6c757d;"></i>
                        <p class="mt-2">Sem imagem</p>
                    </div>
                @endif
                <div class="card-body text-center">
                    <small class="text-muted">Imagem atual</small>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $book->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="author_id" class="form-label">Autor</label>
                        <select class="form-select @error('author_id') is-invalid @enderror" 
                                id="author_id" name="author_id" required>
                            <option value="" disabled>Selecione um autor</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ $author->id == old('author_id', $book->author_id) ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('author_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="publisher_id" class="form-label">Editora</label>
                        <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                id="publisher_id" name="publisher_id" required>
                            <option value="" disabled>Selecione uma editora</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}" {{ $publisher->id == old('publisher_id', $book->publisher_id) ? 'selected' : '' }}>
                                    {{ $publisher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('publisher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Categoria</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="" disabled>Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == old('category_id', $book->category_id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="published_year" class="form-label">Ano de Publicação</label>
                        <input type="number" class="form-control @error('published_year') is-invalid @enderror" 
                               id="published_year" name="published_year" 
                               value="{{ old('published_year', $book->published_year) }}">
                        @error('published_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image_path" class="form-label">Alterar Capa</label>
                    <input type="file" class="form-control @error('image_path') is-invalid @enderror" 
                           id="image_path" name="image_path">
                    <small class="text-muted">Deixe em branco para manter a imagem atual. Formatos: JPEG, PNG (max 2MB)</small>
                    @error('image_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $book->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Atualizar
                    </button>
                    <a href="{{ route('books.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection