@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de la Evaluación {{ $evaluacion->id_evaluacion }}</h1>

    @include('partials.info')
    @include('partials.error')

    <p><strong>Asignación:</strong> {{ $evaluacion->id_asignacion }}</p>
    <p><strong>Docente:</strong> {{ $evaluacion->asignacion->docente->Nombre ?? 'N/A' }}</p>
    <p><strong>Supervisor:</strong> {{ $evaluacion->asignacion->supervisor->Nombre ?? 'N/A' }}</p>
    <p><strong>Semestre:</strong> {{ $evaluacion->asignacion->semestre->nombre_semestre ?? 'N/A' }}</p>
    <p><strong>Fecha de Evaluación:</strong> {{ \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') }}</p>
    <p><strong>Fecha Límite:</strong> {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
    <p><strong>Tipo de Curso:</strong> {{ $evaluacion->tipo_curso }}</p>
    <p><strong>Progreso:</strong> {{ $evaluacion->calcularProgresoTotal() }}%</p>
    <p><strong>Estado:</strong> {{ $evaluacion->calcularProgresoTotal() == 100 ? 'Completa' : 'En Progreso' }}</p>

    @if($fechaVencida && count($faltantes) > 0)
        <div class="alert alert-danger">
            <p><strong>Criterios Obligatorios Pendientes:</strong></p>
            <ul>
                @foreach($faltantes as $idCriterio)
                    @php
                        $criterio = \App\Models\CriterioEvaluacion::find($idCriterio);
                    @endphp
                    <li>{{ $criterio->descripcion_criterio }}</li>
                @endforeach
            </ul>
            <p>La fecha límite ha pasado y aún quedan criterios obligatorios por evaluar.</p>
        </div>
    @elseif(count($faltantes) > 0)
        <div class="alert alert-warning">
            <p><strong>Criterios Obligatorios Pendientes:</strong></p>
            <ul>
                @foreach($faltantes as $idCriterio)
                    @php
                        $criterio = \App\Models\CriterioEvaluacion::find($idCriterio);
                    @endphp
                    <li>{{ $criterio->descripcion_criterio }}</li>
                @endforeach
            </ul>
            <p>Fecha límite para completar: {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
        </div>
    @endif

    <h3>Detalles de los Criterios</h3>
    <table class="table table-bordered">
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

    @if($evaluacion->calcularProgresoTotal() < 100 && now()->lte($fechaFin))
        <a href="{{ route('evaluaciones.continue', ['idEvaluacion' => $evaluacion->id_evaluacion]) }}" class="btn btn-warning">Continuar Evaluación</a>
    @endif

    <a href="{{ route('evaluaciones.show', ['idAsignacion' => $evaluacion->id_asignacion]) }}" class="btn btn-secondary">Volver a Evaluaciones</a>
</div>
@endsection