@extends('layouts.master')

@section('page_title', 'Evaluaciones')

@section('content')
<div class="container">    
    @include('partials.info')

    @if(Auth::user()->hasAnyRole(['ADMINISTRADOR', 'SUPERVISOR']))
        <a href="{{ route('evaluaciones.create') }}" class="btn btn-success mb-3">Crear Nueva Evaluación</a>
    @endif


    @if($evaluaciones->isEmpty())
        <p>No hay evaluaciones registradas.</p>
    @else
        <table class="table">
           <thead>
                <tr>
                    <th>ID Evaluación</th>
                    @if(Auth::user()->hasRole('ADMINISTRADOR'))
                        <th>Supervisor</th>
                    @endif
                    <th>Docente</th>
                    <th>Semestre</th>
                    <th>Tipo de Curso</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluaciones as $evaluacion)
                <tr>
                    <td>{{ $evaluacion->id_evaluacion }}</td>
                    @if(Auth::user()->hasRole('ADMINISTRADOR'))
                        <td>{{ $evaluacion->asignacion->supervisor->Nombre ?? 'N/A' }}</td>
                    @endif
                    <td>{{ $evaluacion->asignacion->docente->Nombre ?? 'N/A' }}</td>
                    <td>{{ $evaluacion->semestre->nombre_semestre ?? 'N/A' }}</td>
                    <td>{{ $evaluacion->tipo_curso}}</td>
                    <td>{{ $evaluacion->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('evaluaciones.show', ['idEvaluacion' => $evaluacion->id_evaluacion]) }}" class="btn btn-info btn-sm">Ver</a>
                        @if(Auth::user()->hasAnyRole(['ADMIN', 'SUPERVISOR']))
                            <a href="{{ route('evaluaciones.continue', ['idEvaluacion' => $evaluacion->id_evaluacion]) }}" class="btn btn-warning btn-sm">Continuar</a>
                        @endif
                        @if(Auth::user()->hasAnyRole(['ADMIN', 'SUPERVISOR']))
                            <form action="{{ route('evaluaciones.destroy', $evaluacion->id_evaluacion) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta evaluación?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        @endif
                    </td>                    
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection