@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Evaluaciones</h1>
    @include('partials.info')

    <a href="{{ route('evaluaciones.create') }}" class="btn btn-success mb-3">Crear Nueva Evaluación</a>

    @if($evaluaciones->isEmpty())
        <p>No hay evaluaciones registradas.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supervisor</th>
                    <th>Docente</th>
                    <th>Semestre</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluaciones as $evaluacion)
                <tr>
                    <td>{{ $evaluacion->id_evaluacion }}</td>
                    <td>{{ $evaluacion->asignacion->supervisor->Nombre ?? 'N/A' }}</td>
                    <td>{{ $evaluacion->asignacion->docente->Nombre ?? 'N/A' }}</td>
                    <td>{{ $evaluacion->semestre->nombre_semestre ?? 'N/A' }}</td>
                    <td>{{ $evaluacion->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection