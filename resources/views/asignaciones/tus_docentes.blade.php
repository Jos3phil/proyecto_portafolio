<!-- resources/views/asignaciones/tus_docentes.blade.php -->

@extends('layouts.master')
@section('content_header')
    <h1>@yield('page_title', 'Docentes Asignados')</h1>
@stop

@section('content')
<div class="container">

    @if(session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
        </div>
    @endif

    @if($asignaciones->isEmpty())
        <p>No tienes docentes asignados.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Asignación</th>
                    <th>ID Docente</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                    <!-- Agrega más columnas si es necesario -->
                </tr>
            </thead>
            <tbody>
                @foreach($asignaciones as $asignacion)
                    <tr>
                        <td>{{ $asignacion->id_asignacion }}</td>
                        <td>{{ $asignacion->docente->id_usuario }}</td>
                        <td>{{ $asignacion->docente->Nombre }}</td>
                        <td>{{ $asignacion->docente->email }}</td>
                        <td>
                            <a href="{{ route('evaluaciones.create', ['id_asignacion' => $asignacion->id_asignacion]) }}" class="btn btn-success btn-sm">
                                Crear Evaluación
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection