/**
 * This is the welcome view for the Proyecto_Portafolio application.
 * 
 * This view extends the 'layouts.app' layout and customizes several sections:
 * - subtitle: Sets the subtitle of the page to 'Welcome'.
 * - content_header_title: Sets the main header title to 'Home'.
 * - content_header_subtitle: Sets the header subtitle to 'Welcome'.
 * 
 * The main content of the page is defined within the 'content_body' section, 
 * which contains a welcome message.
 * 
 * Additional CSS and JavaScript can be included by pushing to the 'css' and 'js' stacks respectively.
 * - The 'css' stack is intended for extra stylesheets.
 * - The 'js' stack includes a script that logs a message to the console.
 */
@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Content body: main page content --}}

@section('content_body')
    <p>Welcome to this beautiful admin panel.</p>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush