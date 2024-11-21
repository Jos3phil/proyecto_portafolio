@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Sección de Evaluación</h1>
    @include('partials.error')
    
    <form method="POST" action="{{ route('secciones.store') }}">
        @csrf
        <div class="form-group">
            <label for="nombre_seccion">Nombre de la Sección</label>
            <input type="text" name="nombre_seccion" id="nombre_seccion" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="descripcion_seccion">Descripción de la Sección</label>
            <textarea name="descripcion_seccion" id="descripcion_seccion" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="obligatoriedad">Obligatoriedad</label>
            <select name="obligatoriedad" id="obligatoriedad" class="form-control" required>
                <option value="1">Obligatoria</option>
                <option value="0">Opcional</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Crear Sección</button>
    </form>
</div>
@endsection