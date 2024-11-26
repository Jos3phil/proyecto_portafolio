@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Evaluaciones de la Asignación {{ $asignacion->id_asignacion }}</h1>
    <p><strong>Docente:</strong> {{ $asignacion->docente->Nombre ?? 'N/A' }}</p>
    <p><strong>Supervisor:</strong> {{ $asignacion->supervisor->Nombre ?? 'N/A' }}</p>
    <p><strong>Semestre:</strong> {{ $asignacion->semestre->nombre_semestre ?? 'N/A' }}</p>

    @include('partials.info')
    @include('partials.error')
    <a href="{{ route('evaluaciones.create', ['id_asignacion' => $asignacion->id_asignacion]) }}" class="btn btn-success mb-3">Nueva Evaluación</a>
    <div class="row">
        @forelse($evaluaciones as $index => $evaluacion)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Evaluación {{ $index + 1 }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') }}</p>
                        <p><strong>Tipo de Curso:</strong> {{ $evaluacion->tipo_curso }}</p>
                        <p><strong>Progreso:</strong> {{ $evaluacion->calcularProgreso() }}%</p>
                        <p><strong>Estado:</strong> {{ $evaluacion->calcularProgreso() == 100 ? 'Completa' : 'En Progreso' }}</p>
                        <!-- Botón "Continuar Evaluación" si el progreso es menor al 100% y dentro del plazo -->
                        @if($evaluacion->calcularProgresoTotal() < 100 && now()->lte($asignacion->semestre->fecha_fin))
                            <a href="{{ route('evaluaciones.continue', ['idEvaluacion' => $evaluacion->id_evaluacion]) }}" class="btn btn-warning">Continuar Evaluación</a>
                        @endif
                         <!-- Botón para ver detalles -->
                        <a href="{{ route('evaluaciones.detail', ['idEvaluacion' => $evaluacion->id_evaluacion]) }}" class="btn btn-primary">Ver Detalles</a>
                        <!-- Botón para eliminar -->
                        <form action="{{ route('evaluaciones.destroy', $evaluacion->id_evaluacion) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta evaluación?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>                
            </div>
        @empty
            <p>no hay evaluaciones disponibles.</p>
        @endforelse        
    </div>
</div>
@endsection