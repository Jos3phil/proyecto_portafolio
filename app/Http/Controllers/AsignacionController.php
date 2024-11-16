<?php
// app/Http/Controllers/AsignacionController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asignacion;
use App\Models\Semestre;
use Illuminate\Http\Request;

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

        $idAsignacion = 'A' . time();

        Asignacion::create([
            'id_asignacion' => $idAsignacion,
            'id_supervisor' => $request->id_supervisor,
            'id_docente' => $request->id_docente,
            'id_semestre' => $request->id_semestre,
        ]);

        return redirect()->route('asignaciones.create')->with('mensaje', 'Asignación creada exitosamente.');
    }
}