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
                </tr>
            </thead>
            <tbody>
                @foreach($secciones as $seccion)
                    <tr>
                        <td>{{ $seccion->nombre_seccion }}</td>
                        <td>{{ $seccion->descripcion_seccion }}</td>
                        <td>{{ $seccion->obligatoriedad ? 'Obligatoria' : 'Opcional' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection