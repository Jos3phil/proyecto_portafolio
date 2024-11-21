<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CriterioEvaluacion;
use App\Models\SeccionEvaluacion;
use App\Models\Evaluacion;

class EvaluacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:SUPERVISOR']);
    }
    public function index()
    {
        // Obtener todas las evaluaciones
        $evaluaciones = Evaluacion::all();

        return view('evaluaciones.index', compact('evaluaciones'));
    }

    public function showEvaluationForm(Request $request)
    {
        $tipoCurso = $request->input('tipo_curso', 'TEORIA'); // Valor por defecto 'TEORIA'

        // Obtener criterios que aplican al tipo de curso o a ambos
        $criterios = CriterioEvaluacion::where('tipo_curso', $tipoCurso)
                    ->orWhere('tipo_curso', 'AMBOS')
                    ->with('seccion')
                    ->get()
                    ->groupBy('seccion.nombre_seccion');

        return view('evaluaciones.create', compact('criterios', 'tipoCurso'));
    }
    public function storeEvaluation(Request $request)
    {
        // Lógica para almacenar la evaluación
    }
}
