<!-- resources/views/asignaciones/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Asignar Supervisor a Docente</h1>

    @include('partials.info')
    @include('partials.error')

    <form method="POST" action="{{ route('asignaciones.store') }}">
        @csrf
        <div class="form-group">
            <label for="id_supervisor">Supervisor</label>
            <select id="id_supervisor" name="id_supervisor" class="form-control">
                @foreach($supervisores as $supervisor)
                    <option value="{{ $supervisor->id_usuario }}">{{ $supervisor->Nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_docente">Docente</label>
            <select id="id_docente" name="id_docente" class="form-control">
                @foreach($docentes as $docente)
                    <option value="{{ $docente->id_usuario }}">{{ $docente->Nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_semestre">Semestre</label>
            <select id="id_semestre" name="id_semestre" class="form-control">
                @foreach($semestres as $semestre)
                    <option value="{{ $semestre->id_semestre }}">{{ $semestre->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Asignar</button>
    </form>
</div>
@endsection