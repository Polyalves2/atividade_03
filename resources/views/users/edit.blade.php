@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Usuário</h1>

    @if(auth()->user()->hasRole('cliente'))
        {{-- Apenas visualização para clientes --}}
        <div class="alert alert-info">
            Você está logado como <strong>Cliente</strong> e só pode visualizar os dados do usuário.
        </div>

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <input type="text" class="form-control" value="{{ ucfirst($user->getRoleNames()->first()) }}" disabled>
        </div>

        <a href="{{ route('users.index') }}" class="btn btn-secondary">Voltar</a>
    @else
        {{-- Formulário editável para admin e bibliotecário --}}
        <form id="edit-user-form" action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $user->name) }}" 
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email', $user->email) }}" 
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo de Role --}}
            @if(auth()->user()->hasRole('admin'))
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" 
                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select id="role" name="role" class="form-select" disabled>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" 
                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="role" value="{{ $user->getRoleNames()->first() }}">
                    <div class="alert alert-warning mt-2">
                        Você está logado como <strong>Bibliotecário</strong> e não possui permissão para alterar as Roles dos usuários.
                    </div>
                </div>
            @endif

            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    @endif
</div>
@endsection
