@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Semestre</h1>
    @include('partials.error')

    <form method="POST" action="{{ route('semestres.store') }}">
        @csrf
        <div class="form-group">
            <label for="id_semestre">ID Semestre</label>
            <input type="text" name="id_semestre" id="id_semestre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nombre_semestre">Nombre Semestre</label>
            <input type="text" name="nombre_semestre" id="nombre_semestre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="fecha_inicio">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="fecha_fin">Fecha Fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Crear Semestre</button>
    </form>
</div>
@endsection