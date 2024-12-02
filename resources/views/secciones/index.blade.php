@extends('layouts.master')

@section('page_title', 'Secciones')

@section('content')
<div class="container">
    <h1>Lista de Secciones de Evaluación</h1>
    @include('partials.info')

    <a href="{{ route('secciones.create') }}" class="btn btn-success mb-3">Crear Nueva Sección</a>

    @if($secciones->isEmpty())
        <p>No hay secciones de evaluación registradas.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Obligatoriedad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($secciones as $seccion)
                    <tr>
                        <td>{{ $seccion->nombre_seccion }}</td>
                        <td>{{ $seccion->descripcion_seccion }}</td>
                        <td>{{ $seccion->obligatoriedad ? 'Obligatoria' : 'Opcional' }}</td>
                        <td>
                            <a href="{{ route('secciones.edit', $seccion->id_seccion) }}" class="btn btn-primary btn-sm">Editar</a>
                                
                            <form action="{{ route('secciones.destroy', $seccion->id_seccion) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta sección?');">Eliminar</button>
                            </form>
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection