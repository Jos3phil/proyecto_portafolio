<?php
// app/Http/Controllers/AsignacionController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asignacion;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsignacionController extends Controller
{
    public function create()
    {
        $supervisores = User::whereHas('roles', function ($query) {
            $query->where('tipo_rol', 'SUPERVISOR');
        })->get();

        $docentes = User::whereHas('roles', function ($query) {
            $query->where('tipo_rol', 'DOCENTE');
        })->get();

        $semestres = Semestre::all();

        return view('asignaciones.create', compact('supervisores', 'docentes', 'semestres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supervisor' => 'required|exists:TUsuario,id_usuario',
            'id_docente' => 'required|exists:TUsuario,id_usuario',
            'id_semestre' => 'required|exists:TSemestre,id_semestre',
        ]);

        // Verificar si ya existe una asignación para este docente y supervisor
        $existingAsignacion = Asignacion::where('id_supervisor', $request->id_supervisor)
                                        ->where('id_docente', $request->id_docente)
                                        ->first();

        if ($existingAsignacion) {
            return redirect()->route('asignaciones.create')->withErrors(['El docente ya está asignado a este supervisor.']);
        }

        $idAsignacion = 'A' . time();
        

        Asignacion::create([
            'id_asignacion' => $idAsignacion,
            'id_supervisor' => $request->id_supervisor,
            'id_docente' => $request->id_docente,
            'id_semestre' => $request->id_semestre,
        ]);

        return redirect()->route('asignaciones.create')->with('mensaje', 'Asignación creada exitosamente.');
    }
    public function index()
    {
        $asignaciones = Asignacion::with(['supervisor', 'docente', 'semestre'])->get();
        return view('asignaciones.index', compact('asignaciones'));
    }
    public function edit($id)
    {
        $asignacion = Asignacion::findOrFail($id);
        $supervisores = User::whereHas('roles', function ($query) {
            $query->where('tipo_rol', 'SUPERVISOR');
        })->get();

        $docentes = User::whereHas('roles', function ($query) {
            $query->where('tipo_rol', 'DOCENTE');
        })->get();

        $semestres = Semestre::all();

        return view('asignaciones.edit', compact('asignacion', 'supervisores', 'docentes', 'semestres'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_supervisor' => 'required|exists:TUsuario,id_usuario',
            'id_docente' => 'required|exists:TUsuario,id_usuario',
            'id_semestre' => 'required|exists:TSemestre,id_semestre',
        ]);

        $asignacion = Asignacion::findOrFail($id);
        $asignacion->update([
            'id_supervisor' => $request->id_supervisor,
            'id_docente' => $request->id_docente,
            'id_semestre' => $request->id_semestre,
        ]);

        return redirect()->route('asignaciones.index')->with('mensaje', 'Asignación actualizada exitosamente.');
    }

    /**
     * Mostrar los docentes asignados al supervisor autenticado
     */
    public function tusDocentes()
    {
        $supervisor = Auth::user();

        // Verificar que el usuario tenga el rol de SUPERVISOR
        if (!$supervisor->hasRole('SUPERVISOR')) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        // Obtener las asignaciones del supervisor con la información del docente
        $asignaciones = Asignacion::with('docente')
        ->where('id_supervisor', $supervisor->id_usuario)
        ->get();

    return view('asignaciones.tus_docentes', compact('asignaciones'));
    }
    public function destroy($id)
    {
        $asignacion = Asignacion::findOrFail($id);
        $asignacion->delete();

        return redirect()->route('asignaciones.index')->with('mensaje', 'Asignación eliminada exitosamente.');
    }
}