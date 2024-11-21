@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Criterio de Evaluación</h1>
    @include('partials.error')

    <form method="POST" action="{{ route('criterios.store') }}">
        @csrf
        <div class="form-group">
            <label for="descripcion_criterio">Descripción del Criterio</label>
            <input type="text" name="descripcion_criterio" id="descripcion_criterio" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="id_seccion">Sección</label>
            <select name="id_seccion" id="id_seccion" class="form-control" required>
                @foreach($secciones as $seccion)
                    <option value="{{ $seccion->id_seccion }}">{{ $seccion->nombre_seccion }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="tipo_curso">Tipo de Curso</label>
            <select name="tipo_curso" id="tipo_curso" class="form-control" required>
                <option value="TEORIA">Teoría</option>
                <option value="PRACTICA">Práctica</option>
                <option value="AMBOS">Ambos</option>
            </select>
        </div>
        <div class="form-group">
            <label for="obligatoriedad">Obligatoriedad</label>
            <select name="obligatoriedad" id="obligatoriedad" class="form-control" required>
                <option value="1">Obligatorio</option>
                <option value="0">Opcional</option>
            </select>
        </div>
        <div class="form-group">
            <label for="peso">Peso</label>
            <input type="number" name="peso" id="peso" class="form-control" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Crear Criterio</button>
    </form>
</div>
@endsection