@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Lista de Livros</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex gap-2 mb-3">
        <a href="{{ route('books.create.id') }}" class="btn btn-outline-primary">
            <i class="bi bi-plus"></i> Criar com IDs
        </a>
        <a href="{{ route('books.create.select') }}" class="btn btn-outline-secondary">
            <i class="bi bi-plus"></i> Criar com Selects
        </a>
    </div>

    <div class="row">
        <!-- Lista de Livros -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Capa</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr>
                                    <td>{{ $book->id }}</td>
                                    <td>
                                        @if($book->image_url)
                                            <img src="{{ $book->image_url }}" 
                                                 class="img-thumbnail" 
                                                 alt="Capa de {{ $book->title }}"
                                                 style="width: 50px; height: 70px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 70px;">
                                                <i class="bi bi-book text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author->name }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir este livro?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">Nenhum livro encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Empréstimos -->
        <div class="col-md-4">
            @isset($book)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        Registrar Empréstimo
                    </div>
                    <div class="card-body">
                        <form action="{{ route('books.borrow', $book) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Usuário</label>
                                <select class="form-select" id="user_id" name="user_id" required>
                                    <option value="">Selecione um usuário</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-bookmark-plus"></i> Registrar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        Histórico de Empréstimos do Livro
                    </div>
                    <div class="card-body">
                        @if($book->users->isEmpty())
                            <p class="text-muted">Nenhum empréstimo registrado.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($book->users as $user)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('users.show', $user->id) }}">
                                                        {{ $user->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($user->pivot->returned_at)
                                                        Devolvido em {{ $user->pivot->returned_at->format('d/m/Y') }}
                                                    @else
                                                        <span class="badge bg-warning text-dark">Em Aberto</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(is_null($user->pivot->returned_at))
                                                        <form action="{{ route('borrowings.return', $user->pivot->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-sm btn-warning">
                                                                <i class="bi bi-arrow-return-left"></i> Devolver
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endisset

            @isset($user)
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        Histórico do Usuário
                    </div>
                    <div class="card-body">
                        @if($user->books->isEmpty())
                            <p class="text-muted">Nenhum empréstimo registrado.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Livro</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->books as $book)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('books.show', $book->id) }}">
                                                        {{ $book->title }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($book->pivot->returned_at)
                                                        Devolvido em {{ $book->pivot->returned_at->format('d/m/Y') }}
                                                    @else
                                                        <span class="badge bg-warning text-dark">Em Aberto</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(is_null($book->pivot->returned_at))
                                                        <form action="{{ route('borrowings.return', $book->pivot->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-sm btn-warning">
                                                                <i class="bi bi-arrow-return-left"></i> Devolver
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endisset
        </div>
    </div>
</div>
@endsection