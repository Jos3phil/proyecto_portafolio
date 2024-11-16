@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Asignar rol a usuario</h1>
    @include('partials.info')
    @include('partials.error')
    <form method="POST" action="{{ route('users.assignRole', $user->id_usuario) }}">
        @csrf
        <div class="form-group">
            <label for="role">Rol</label>
            <select id="role" name="role" class="form-control">
                <option value="DOCENTE">DOCENTE</option>
                <option value="SUPERVISOR">SUPERVISOR</option>
                <option value="ADMINISTRADOR">ADMINISTRADOR</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign Role</button>
    </form>
</div>
@endsection