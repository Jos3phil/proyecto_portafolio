<!-- resources/views/layouts/master.blade.php -->

@extends('adminlte::page')

@section('title', config('app.name', 'Laravel'))

@section('content_header')
    <h1>@yield('page_title', 'Bienvenido')</h1>
@stop

@section('content')
    @include('partials.info')
    @yield('content')
@stop

@section('css')
    <!-- CSS adicional si es necesario -->
    @stack('css')
@stop

@section('js')
    <!-- JavaScript adicional si es necesario -->
    @stack('js')
@stop