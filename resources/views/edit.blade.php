@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Editar Livro</h1>

    <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" 
                   class="form-control @error('title') is-invalid @enderror" 
                   id="title" name="title" 
                   value="{{ old('title', $book->title) }}" 
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="publisher_id" class="form-label">Editora</label>
            <select class="form-select @error('publisher_id') is-invalid @enderror" 
                    id="publisher_id" name="publisher_id" required>
                <option value="" disabled {{ old('publisher_id', $book->publisher_id) ? '' : 'selected' }}>
                    Selecione uma editora
                </option>
                @foreach($publishers as $publisher)
                    <option value="{{ $publisher->id }}" 
                        {{ (old('publisher_id', $book->publisher_id) == $publisher->id) ? 'selected' : '' }}>
                        {{ $publisher->name }}
                    </option>
                @endforeach
            </select>
            @error('publisher_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="author_id" class="form-label">Autor</label>
            <select class="form-select @error('author_id') is-invalid @enderror" 
                    id="author_id" name="author_id" required>
                <option value="" disabled {{ old('author_id', $book->author_id) ? '' : 'selected' }}>
                    Selecione um autor
                </option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" 
                        {{ (old('author_id', $book->author_id) == $author->id) ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>
            @error('author_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Categoria</label>
            <select class="form-select @error('category_id') is-invalid @enderror" 
                    id="category_id" name="category_id" required>
                <option value="" disabled {{ old('category_id', $book->category_id) ? '' : 'selected' }}>
                    Selecione uma categoria
                </option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ (old('category_id', $book->category_id) == $category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Capa do Livro</label>
            @if($book->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/livros/' . $book->image) }}?v={{ time() }}" 
                         id="preview-image" 
                         style="max-height: 200px;" 
                         class="img-thumbnail" 
                         alt="Capa do livro">
                </div>
            @else
                <div class="mb-2">
                    <img id="preview-image" 
                         style="max-height: 200px; display: none;" 
                         class="img-thumbnail" 
                         alt="Preview da capa do livro">
                </div>
            @endif
            <input type="file" 
                   class="form-control @error('image') is-invalid @enderror" 
                   id="image" name="image" accept="image/*">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-image');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    });
</script>
@endsection
