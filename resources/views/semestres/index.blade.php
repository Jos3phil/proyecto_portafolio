@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Semestres</h1>
    @include('partials.info')

    <a href="{{ route('semestres.create') }}" class="btn btn-success mb-3">Crear Nuevo Semestre</a>

    @if($semestres->isEmpty())
        <p>No hay semestres registrados.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($semestres as $semestre)
                    <tr>
                        <td>{{ $semestre->id_semestre }}</td>
                        <td>{{ $semestre->nombre_semestre }}</td>
                        <td>{{ $semestre->fecha_inicio }}</td>
                        <td>{{ $semestre->fecha_fin }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection