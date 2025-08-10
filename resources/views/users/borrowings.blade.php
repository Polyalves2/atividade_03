@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Empréstimos de {{ $user->name }}</h1>

    @if($borrowings->isEmpty())
        <p>Nenhum empréstimo encontrado.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Título do Livro</th>
                    <th>Data de Empréstimo</th>
                    <th>Data de Devolução</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowings as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->pivot->borrowed_at }}</td>
                        <td>{{ $book->pivot->returned_at ?? 'Em aberto' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
