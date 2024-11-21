<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeccionEvaluacion;

class SeccionEvaluacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMINISTRADOR']);
    }

    public function create()
    {
        return view('secciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_seccion' => 'required|string|max:255',
            'descripcion_seccion' => 'nullable|string',
            'obligatoriedad' => 'required|boolean',
        ]);

        SeccionEvaluacion::create($request->all());

        return redirect()->route('secciones.index')->with('mensaje', 'Sección creada exitosamente.');
    }

    public function index()
    {
        $secciones = SeccionEvaluacion::all();
        return view('secciones.index', compact('secciones'));
    }
}
