@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Asignación</h1>
    @include('partials.error')

    <form method="POST" action="{{ route('asignaciones.update', $asignacion->id_asignacion) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id_supervisor">Supervisor</label>
            <select id="id_supervisor" name="id_supervisor" class="form-control">
                @foreach($supervisores as $supervisor)
                    <option value="{{ $supervisor->id_usuario }}" {{ $asignacion->id_supervisor == $supervisor->id_usuario ? 'selected' : '' }}>{{ $supervisor->Nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_docente">Docente</label>
            <select id="id_docente" name="id_docente" class="form-control">
                @foreach($docentes as $docente)
                    <option value="{{ $docente->id_usuario }}" {{ $asignacion->id_docente == $docente->id_usuario ? 'selected' : '' }}>{{ $docente->Nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_semestre">Semestre</label>
            <select id="id_semestre" name="id_semestre" class="form-control">
                @foreach($semestres as $semestre)
                    <option value="{{ $semestre->id_semestre }}" {{ $asignacion->id_semestre == $semestre->id_semestre ? 'selected' : '' }}>{{ $semestre->nombre_semestre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection