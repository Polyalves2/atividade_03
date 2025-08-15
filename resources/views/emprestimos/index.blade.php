@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Empréstimos</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Livro</th>
                <th>Usuário</th>
                <th>Data Empréstimo</th>
                <th>Data Devolução</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emprestimos as $emprestimo)
            <tr>
                <td>{{ $emprestimo->livro->titulo }}</td>
                <td>{{ $emprestimo->user->name }}</td>
                <td>{{ $emprestimo->data_emprestimo->format('d/m/Y') }}</td>
                <td>{{ $emprestimo->data_devolucao ? $emprestimo->data_devolucao->format('d/m/Y') : 'Não devolvido' }}</td>
                <td>
                    @if(!$emprestimo->data_devolucao)
                        <form action="{{ route('emprestimos.devolver', $emprestimo->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">Devolver</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('emprestimos.create') }}" class="btn btn-primary">Novo Empréstimo</a>
</div>
@endsection