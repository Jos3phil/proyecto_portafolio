@extends('layouts.master')

@section('page_title', 'Criterios')

@section('content')
<div class="container">
    <h1>Lista de Criterios de Evaluación</h1>
    @include('partials.info')

    <a href="{{ route('criterios.create') }}" class="btn btn-success mb-3">Crear Nuevo Criterio</a>

    @if($criterios->isEmpty())
        <p>No hay criterios de evaluación registrados.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Sección</th>
                    <th>Tipo de Curso</th>
                    <th>Obligatoriedad</th>
                    <th>Peso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($criterios as $criterio)
                    <tr>
                        <td>{{ $criterio->descripcion_criterio }}</td>
                        <td>{{ $criterio->seccion->nombre_seccion }}</td>
                        <td>{{ $criterio->tipo_curso }}</td>
                        <td>{{ $criterio->obligatoriedad ? 'Obligatorio' : 'Opcional' }}</td>
                        <td>{{ $criterio->peso }}</td>
                        <td>
                            <a href="{{ route('criterios.edit', $criterio->id_criterio) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('criterios.destroy', $criterio->id_criterio) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este criterio?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection