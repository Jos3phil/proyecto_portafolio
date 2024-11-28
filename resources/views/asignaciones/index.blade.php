@extends('layouts.master')

@section('page_title', 'Asignaciones')

@section('content')
<div class="container">
    @include('partials.info')

    <a href="{{ route('asignaciones.create') }}" class="btn btn-success mb-3">Crear Nueva Asignación</a>

    @if($asignaciones->isEmpty())
        <p>No hay asignaciones registradas.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supervisor</th>
                    <th>Docente</th>
                    <th>Semestre</th>
                    <th>Fecha de Creación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asignaciones as $asignacion)
                    <tr>
                        <td>{{ $asignacion->id_asignacion }}</td>
                        <td>{{ $asignacion->supervisor->Nombre }}</td>
                        <td>{{ $asignacion->docente->Nombre }}</td>
                        <td>{{ $asignacion->semestre->id_semestre }}</td>
                        <td>{{ $asignacion->created_at }}</td>
                        <td>
                            <a href="{{ route('asignaciones.edit', $asignacion->id_asignacion) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('asignaciones.destroy', $asignacion->id_asignacion) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta asignación?')">Eliminar</button>
                            </form>
                            <a href="{{ route('evaluaciones.show', $asignacion->id_asignacion) }}" class="btn btn-primary btn-sm">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection