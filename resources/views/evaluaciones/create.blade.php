@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Evaluación de Portafolio</h1>

    <!-- Formulario para seleccionar el tipo de curso -->
    <form method="GET" action="{{ route('evaluaciones.create') }}" class="mb-4">
        <input type="hidden" name="id_asignacion" value="{{ $idAsignacion }}">
        <div class="form-group">
            <label for="tipo_curso">Tipo de Curso</label>
            <select name="tipo_curso" id="tipo_curso" class="form-control" onchange="this.form.submit()">
                <option value="TEORIA" {{ $tipoCurso == 'TEORIA' ? 'selected' : '' }}>Teoría</option>
                <option value="PRACTICA" {{ $tipoCurso == 'PRACTICA' ? 'selected' : '' }}>Práctica</option>
            </select>
        </div>
    </form>

    <!-- Formulario para la evaluación -->
    <form method="POST" action="{{ route('evaluaciones.store') }}">
        @csrf
        <input type="hidden" name="id_asignacion" value="{{ $idAsignacion }}">
        <input type="hidden" name="tipo_curso" value="{{ $tipoCurso }}">
        @foreach($criterios as $nombreSeccion => $criteriosSeccion)
            <h3>{{ $nombreSeccion }}</h3>
            @foreach($criteriosSeccion as $criterio)
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="criterios[{{ $criterio->id_criterio }}]" value="1">
                        {{ $criterio->descripcion_criterio }}
                        @if($criterio->obligatorio)
                            <span class="text-danger">*</span>
                        @endif
                        (Peso: {{ $criterio->peso }})
                    </label>
                    <input type="text" name="comentarios[{{ $criterio->id_criterio }}]" class="form-control" placeholder="Comentario opcional">
                </div>
            @endforeach
        @endforeach
        <button type="submit" class="btn btn-primary mt-3">Guardar Evaluación</button>
    </form>
</div>
@endsection