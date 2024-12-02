<!-- resources/views/users/index.blade.php -->

@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista de Usuarios</h1>

    @if(session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id_usuario }}</td>
                    <td>{{ $user->Nombre }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary">{{ $role->tipo_rol }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('users.showAssignRoleForm', $user->id_usuario) }}" class="btn btn-sm btn-warning">Asignar Rol</a>
                        <!-- Agrega más acciones si es necesario -->
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay usuarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
</div>
@endsection