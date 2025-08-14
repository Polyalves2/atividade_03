@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Atualizar papel de usuário</h2>

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.roles.update') }}">
        @csrf

        <div class="form-group">
            <label for="email">E-mail do usuário:</label>
            <input type="email" name="email" id="email" required class="form-control">
            @error('email')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mt-2">
            <label for="role">Novo papel:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="admin">Admin</option>
                <option value="bibliotecario">Bibliotecário</option>
                <option value="user">Usuário</option>
            </select>
            @error('role')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Atualizar Papel</button>
    </form>
</div>
@endsection
