<!-- resources/views/roles/switch.blade.php -->

@extends('layouts.master')

@section('page_title', 'Cambiar Rol Activo')

@section('content')
<div class="container">
    <h2>Cambiar Rol Activo</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('roles.setActive') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="role">Selecciona tu rol:</label>
            <select name="role" id="role" class="form-control">
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ Auth::user()->getActiveRole() == $role ? 'selected' : '' }}>
                        {{ ucfirst(strtolower($role)) }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-2">Cambiar Rol</button>
    </form>
</div>
@endsection