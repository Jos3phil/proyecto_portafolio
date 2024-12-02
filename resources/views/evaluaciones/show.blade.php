@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle de Evaluación: {{ $evaluacion->id_evaluacion }}</h1>

    <div class="card mb-4">
        <div class="card-header">
            Información de la Evaluación
        </div>
        <div class="card-body">
            @if(Auth::user()->hasRole('ADMIN'))
                <p><strong>Supervisor:</strong> {{ $evaluacion->asignacion->supervisor->Nombre ?? 'N/A' }}</p>
            @endif
            <p><strong>Docente:</strong> {{ $evaluacion->asignacion->docente->Nombre ?? 'N/A' }}</p>
            <p><strong>Semestre:</strong> {{ $evaluacion->semestre->nombre_semestre ?? 'N/A' }}</p>
            <p><strong>Tipo de Curso:</strong> {{ $evaluacion->tipo_curso }}</p>
            <p><strong>Fecha de Evaluación:</strong> {{ \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') }}</p>
        </div>
    </div>

    <h3>Detalles de la Evaluación</h3>
    @if($evaluacion->detalles->isEmpty())
        <p>No hay detalles registrados para esta evaluación.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Criterio</th>
                    <th>Cumple</th>
                    <th>Comentarios</th>
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
    @endif

    <a href="{{ route('evaluaciones.index') }}" class="btn btn-secondary">Volver al Índice</a>
</div>
@endsection