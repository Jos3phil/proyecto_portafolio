@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Continuar Evaluación</h1>
    <p><strong>Asignación:</strong> {{ $idAsignacion }}</p>
    <p><strong>Tipo de Curso:</strong> {{ $tipoCurso }}</p>
    <!-- Comprobar si existe la variable $fechaFin -->
    @if(isset($fechaFin))
        <p><strong>Fecha Límite:</strong> {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
    @endif
    @include('partials.info')
    @include('partials.error')

    <form method="POST" action="{{ route('evaluaciones.storeContinuation') }}">
        @csrf
        <input type="hidden" name="id_asignacion" value="{{ $idAsignacion }}">
        <input type="hidden" name="tipo_curso" value="{{ $tipoCurso }}">
        <input type="hidden" name="id_evaluacion_anterior" value="{{ $evaluacionAnterior->id_evaluacion }}">

        @foreach($criterios as $nombreSeccion => $criteriosSeccion)
            <h3>{{ $nombreSeccion }}</h3>
            @foreach($criteriosSeccion as $criterio)
                @php
                    $yaEvaluado = in_array($criterio->id_criterio, $criteriosEvaluados);
                @endphp
                <div class="form-group">
                        <label>
                            @if($yaEvaluado)
                                <!-- Mostrar como deshabilitado o tachado -->
                                <input type="checkbox" disabled checked>
                                <s>{{ $criterio->descripcion_criterio }}</s>
                            @else
                                <!-- Campo habilitado para evaluar -->
                                <input type="checkbox" name="criterios[{{ $criterio->id_criterio }}]" value="1">
                                {{ $criterio->descripcion_criterio }}
                            @endif
                            @if($criterio->obligatoriedad)
                                <span class="text-danger">*</span>
                            @endif
                            (Peso: {{ $criterio->peso }})
                        </label>
                        @if(!$yaEvaluado)
                        <input type="text" name="comentarios[{{ $criterio->id_criterio }}]" class="form-control" placeholder="Comentario opcional">
                        @endif
                    </div>
                
            @endforeach
        @endforeach

        <button type="submit" class="btn btn-primary mt-3">Guardar Evaluación</button>
    </form>
</div>
@endsection