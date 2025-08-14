@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Usuários</h1>

    {{-- Mensagens flash --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Role atual</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->getRoleNames()->first() ?? 'Sem role') }}</td>
                <td>
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">Visualizar</a>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
@endsection