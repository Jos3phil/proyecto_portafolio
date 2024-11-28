<!-- resources/views/asignaciones/tus_docentes.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tus Docentes Asignados</h1>

    @if(session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
        </div>
    @endif

    @if($docentes->isEmpty())
        <div class="alert alert-info">
            No tienes docentes asignados.
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Docente</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <!-- Agrega más columnas si es necesario -->
                </tr>
            </thead>
            <tbody>
                @foreach($docentes as $docente)
                    <tr>
                        <td>{{ $docente->id_usuario }}</td>
                        <td>{{ $docente->Nombre }}</td>
                        <td>{{ $docente->email }}</td>
                        <td>
                            @foreach($docente->roles as $role)
                                <span class="badge bg-primary">{{ $role->tipo_rol }}</span>
                            @endforeach
                        </td>
                        <!-- Agrega más celdas si es necesario -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection