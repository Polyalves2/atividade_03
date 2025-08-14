@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Detalhes do Usuário</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Nome:</strong> {{ $user->name ?? 'Não informado' }}</p>
            <p><strong>Email:</strong> {{ $user->email ?? 'Não informado' }}</p>
            <p><strong>Criado em:</strong> {{ $user->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
            <p><strong>Role:</strong> {{ $user->roles?->pluck('name')->implode(', ') ?? 'Nenhum atribuído' }}</p>
        </div>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>
@endsection