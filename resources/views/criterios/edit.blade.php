@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Criterio de Evaluación</h1>
    @include('partials.error')
   

    <form method="POST" action="{{ route('criterios.update', $criterio->id_criterio) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="descripcion_criterio">Descripción del Criterio</label>
            <input type="text" name="descripcion_criterio" id="descripcion_criterio" class="form-control" value="{{ $criterio->descripcion_criterio }}" required>
        </div>
        <div class="form-group">
            <label for="id_seccion">Sección</label>
            <select name="id_seccion" id="id_seccion" class="form-control" required>
                @foreach($secciones as $seccion)
                    <option value="{{ $seccion->id_seccion }}" {{ $criterio->id_seccion == $seccion->id_seccion ? 'selected' : '' }}>{{ $seccion->nombre_seccion }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="tipo_curso">Tipo de Curso</label>
            <select name="tipo_curso" id="tipo_curso" class="form-control" required>
                <option value="TEORIA" {{ $criterio->tipo_curso == 'TEORIA' ? 'selected' : '' }}>Teoría</option>
                <option value="PRACTICA" {{ $criterio->tipo_curso == 'PRACTICA' ? 'selected' : '' }}>Práctica</option>
                <option value="AMBOS" {{ $criterio->tipo_curso == 'AMBOS' ? 'selected' : '' }}>Ambos</option>
            </select>
        </div>
        <div class="form-group">
            <label for="obligatoriedad">Obligatoriedad</label>
            <select name="obligatoriedad" id="obligatoriedad" class="form-control" required>
                <option value="1" {{ $criterio->obligatoriedad ? 'selected' : '' }}>Obligatorio</option>
                <option value="0" {{ !$criterio->obligatoriedad ? 'selected' : '' }}>Opcional</option>
            </select>
        </div>
        <div class="form-group">
            <label for="peso">Peso</label>
            <input type="number" name="peso" id="peso" class="form-control" value="{{ $criterio->peso }}" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Criterio</button>
    </form>
</div>
@endsection