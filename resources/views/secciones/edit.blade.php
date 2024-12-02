
@extends('layouts.master')

@section('page_title', 'Editar Sección')

@section('content')
<div class="container">
    <h1>Editar Sección de Evaluación</h1>
    @include('partials.info')

    <form action="{{ route('secciones.update', $seccion->id_seccion) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nombre de la Sección -->
        <div class="form-group">
            <label for="nombre_seccion">Nombre de la Sección</label>
            <input type="text" name="nombre_seccion" id="nombre_seccion" class="form-control" value="{{ old('nombre_seccion', $seccion->nombre_seccion) }}" required>
            @error('nombre_seccion')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Descripción de la Sección -->
        <div class="form-group">
            <label for="descripcion_seccion">Descripción</label>
            <textarea name="descripcion_seccion" id="descripcion_seccion" class="form-control">{{ old('descripcion_seccion', $seccion->descripcion_seccion) }}</textarea>
            @error('descripcion_seccion')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Obligatoriedad -->
        <div class="form-group">
            <label for="obligatoriedad">Obligatoriedad</label>
            <select name="obligatoriedad" id="obligatoriedad" class="form-control" required>
                <option value="1" {{ old('obligatoriedad', $seccion->obligatoriedad) ? 'selected' : '' }}>Obligatoria</option>
                <option value="0" {{ !old('obligatoriedad', $seccion->obligatoriedad) ? 'selected' : '' }}>Opcional</option>
            </select>
            @error('obligatoriedad')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Botones de Acción -->
        <button type="submit" class="btn btn-primary">Actualizar Sección</button>
        <a href="{{ route('secciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection