<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CriterioEvaluacion;
use App\Models\SeccionEvaluacion;

class CriterioEvaluacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMINISTRADOR']);
    }

    public function create()
    {
        $secciones = SeccionEvaluacion::all();
        return view('criterios.create', compact('secciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion_criterio' => 'required|string|max:255',
            'id_seccion' => 'required|exists:TSeccionesEvaluacion,id_seccion',
            'tipo_curso' => 'required|in:TEORIA,PRACTICA,AMBOS',
            'obligatoriedad' => 'required|boolean',
            'peso' => 'required|integer|min:0',
        ]);

        CriterioEvaluacion::create($request->all());

        return redirect()->route('criterios.index')->with('mensaje', 'Criterio creado exitosamente.');
    }

    public function index()
    {
        $criterios = CriterioEvaluacion::with('seccion')->get();
        return view('criterios.index', compact('criterios'));
    }
    public function edit($id)
    {
        $criterio = CriterioEvaluacion::findOrFail($id);
        $secciones = SeccionEvaluacion::all();
        return view('criterios.edit', compact('criterio', 'secciones'));
    }

    public function update(Request $request, $id)
    {
        $criterio = CriterioEvaluacion::findOrFail($id);

        $request->validate([
            'descripcion_criterio' => 'required|string|max:255',
            'id_seccion' => 'required|exists:TSeccionesEvaluacion,id_seccion',
            'tipo_curso' => 'required|in:TEORIA,PRACTICA,AMBOS',
            'obligatoriedad' => 'required|boolean',
            'peso' => 'required|integer|min:0',
        ]);

        $criterio->update($request->all());

        return redirect()->route('criterios.index')->with('mensaje', 'Criterio actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $criterio = CriterioEvaluacion::findOrFail($id);
        $criterio->delete();

        return redirect()->route('criterios.index')->with('mensaje', 'Criterio eliminado exitosamente.');
    }
}
