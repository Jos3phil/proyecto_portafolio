<!-- resources/views/evaluaciones/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nueva Evaluación</h1>

    @include('partials.info')
    @include('partials.error')
    <!-- Comprobar si existe la variable $asignacion -->
    @if(isset($asignacion))
        <p><strong>Docente:</strong> {{ $asignacion->docente->Nombre }}</p>
        <p><strong>Fecha Límite:</strong> {{ \Carbon\Carbon::parse($asignacion->semestre->fecha_fin)->format('d/m/Y') }}</p>
    @endif
    <!-- Formulario para seleccionar el tipo de curso -->
     
    <!-- Información de la Asignación -->
    <div class="mb-4">
        
        <p><strong>Email:</strong> {{ $asignacion->docente->email }}</p>
        <p><strong>Semestre:</strong> {{ $asignacion->semestre->nombre_semestre }}</p>
    </div>

    <!-- <form method="GET" action="{{ route('evaluaciones.create') }}">
        <input type="hidden" name="id_asignacion" value="{{ $idAsignacion ?? old('id_asignacion') }}">
        <div class="form-group">
            <label for="tipo_curso">Tipo de Curso</label>
            <select name="tipo_curso" id="tipo_curso" class="form-control" onchange="this.form.submit()">
                <option value="TEORIA" {{ $tipoCurso == 'TEORIA' ? 'selected' : '' }}>Teoría</option>
                <option value="PRACTICA" {{ $tipoCurso == 'PRACTICA' ? 'selected' : '' }}>Práctica</option>
            </select>
        </div>
    </form> -->

    <!-- Formulario para crear la evaluación -->
    <form method="POST" action="{{ route('evaluaciones.store') }}">
        @csrf
        <input type="hidden" name="id_asignacion" value="{{ $idAsignacion ?? old('id_asignacion') }}">
        <input type="hidden" name="tipo_curso" value="{{ $tipoCurso ?? old('tipo_curso') }}">
        <!-- Selección del Tipo de Curso -->
        <div class="form-group">
            <label for="tipo_curso">Tipo de Curso</label>
            <select name="tipo_curso" id="tipo_curso" class="form-control" required>
                <option value="TEORIA" {{ old('tipo_curso') == 'TEORIA' ? 'selected' : '' }}>Teoría</option>
                <option value="PRACTICA" {{ old('tipo_curso') == 'PRACTICA' ? 'selected' : '' }}>Práctica</option>
               
            </select>
        </div>

        @foreach($criterios as $nombreSeccion => $criteriosSeccion)
            <h3>{{ $nombreSeccion }}</h3>
            @foreach($criteriosSeccion as $criterio)
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="criterios[{{ $criterio->id_criterio }}]" value="1">
                        {{ $criterio->descripcion_criterio }}
                        @if($criterio->obligatoriedad)
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