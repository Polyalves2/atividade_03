@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Adicionar Livro (Com ID)</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('books.store.id') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="author_id" class="form-label">ID do Autor</label>
                        <input type="number" class="form-control @error('author_id') is-invalid @enderror"
                               id="author_id" name="author_id" value="{{ old('author_id') }}" required>
                        @error('author_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="publisher_id" class="form-label">ID da Editora</label>
                        <input type="number" class="form-control @error('publisher_id') is-invalid @enderror"
                               id="publisher_id" name="publisher_id" value="{{ old('publisher_id') }}" required>
                        @error('publisher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">ID da Categoria</label>
                        <input type="number" class="form-control @error('category_id') is-invalid @enderror"
                               id="category_id" name="category_id" value="{{ old('category_id') }}" required>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="published_year" class="form-label">Ano de Publicação</label>
                        <input type="number" class="form-control @error('published_year') is-invalid @enderror"
                               id="published_year" name="published_year" value="{{ old('published_year') }}">
                        @error('published_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Capa do Livro</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="image_path" class="form-label">Selecione uma imagem</label>
                            <input type="file" class="form-control @error('image_path') is-invalid @enderror"
                                   id="image_path" name="image_path">
                            @error('image_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Formatos: JPEG, PNG (max 2MB)</small>
                        </div>

                        <div class="text-center mt-3">
                            <div id="imagePreview" class="border p-2" style="display: none;">
                                <img id="previewImage" src="#" alt="Pré-visualização da imagem" 
                                     class="img-fluid" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Salvar
            </button>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    // Pré-visualização da imagem antes do upload
    document.getElementById('image_path').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection