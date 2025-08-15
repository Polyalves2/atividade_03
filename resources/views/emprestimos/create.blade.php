@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Empréstimo</h1>
    
    <form action="{{ route('emprestimos.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="livro_id">Livro</label>
            <select name="livro_id" id="livro_id" class="form-control" required>
                <option value="">Selecione um livro</option>
                @foreach($livros as $livro)
                    <option value="{{ $livro->id }}">{{ $livro->titulo }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="user_id">Usuário</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Selecione um usuário</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar Empréstimo</button>
    </form>
</div>
@endsection