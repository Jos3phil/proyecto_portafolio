@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Evaluación de {{ $asignacion->docente->Nombre }}</h1>
    <p><strong>Supervisor:</strong> {{ $asignacion->supervisor->Nombre }}</p>
    <p><strong>Semestre:</strong> {{ $asignacion->semestre->nombre_semestre }}</p>

    @if($evaluaciones->isEmpty())
        <p>No hay evaluaciones registradas para esta asignación.</p>
        <a href="{{ route('evaluaciones.create', ['id_asignacion' => $asignacion->id_asignacion]) }}" class="btn btn-primary">Crear Evaluación</a>
    @else
        @foreach($evaluaciones as $evaluacion)
            <h3>Evaluación {{ $evaluacion->id_evaluacion }}</h3>
            <p><strong>Progreso:</strong> {{ $evaluacion->calcularProgreso()  }}%</p>
        
            <table class="table">
                <thead>
                    <tr>
                        <th>Criterio</th>
                        <th>Cumple</th>
                        <th>Comentario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluacion->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->criterio->descripcion_criterio }}</td>
                            <td>{{ $detalle->cumple ? 'Sí' : 'No' }}</td>
                            <td>{{ $detalle->comentario }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif
</div>
@endsection