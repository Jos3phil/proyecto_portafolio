<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semestre;

class SemestreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMINISTRADOR']);
    }

    public function index()
    {
        $semestres = Semestre::all();
        return view('semestres.index', compact('semestres'));
    }

    public function create()
    {
        return view('semestres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_semestre' => 'required|string|max:255|unique:TSemestre,id_semestre',
            'nombre_semestre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        Semestre::create($request->all());

        return redirect()->route('semestres.index')->with('mensaje', 'Semestre creado exitosamente.');
    }
}
