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
    public function edit($id)
    {
        $seccion = SeccionEvaluacion::findOrFail($id);
        $this->authorize('update', $seccion);

        return view('secciones.edit', compact('seccion'));
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
    public function update(Request $request, $id)
    {
        $seccion = SeccionEvaluacion::findOrFail($id);
        $this->authorize('update', $seccion);

        $request->validate([
            'nombre_seccion' => 'required|string|max:255',
            'descripcion_seccion' => 'nullable|string',
            'obligatoriedad' => 'required|boolean',
        ]);

        $seccion->update([
            'nombre_seccion' => $request->nombre_seccion,
            'descripcion_seccion' => $request->descripcion_seccion,
            'obligatoriedad' => $request->obligatoriedad,
        ]);

        return redirect()->route('secciones.index')->with('mensaje', 'Sección actualizada exitosamente.');
    }

    /**
     * Eliminar la sección de la base de datos.
     */
    public function destroy($id)
    {
        $seccion = SeccionEvaluacion::findOrFail($id);
        $this->authorize('delete', $seccion);

        $seccion->delete();

        return redirect()->route('secciones.index')->with('mensaje', 'Sección eliminada exitosamente.');
    }
}
