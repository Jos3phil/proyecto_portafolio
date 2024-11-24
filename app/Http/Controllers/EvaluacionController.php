<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CriterioEvaluacion;
use App\Models\SeccionEvaluacion;
use App\Models\Evaluacion;
use App\Models\Asignacion;
use Illuminate\Support\Facades\Auth;


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
    public function show($id)
    {
        $asignacion = Asignacion::with(['supervisor', 'docente', 'semestre'])->findOrFail($id);
        $evaluaciones = Evaluacion::where('id_asignacion', $id)->with('detalles.criterio')->get();

        return view('evaluaciones.show', compact('asignacion', 'evaluaciones'));    
       
    }
    public function storeEvaluation(Request $request)
    {
        $request->validate([
            'id_asignacion' => 'required|exists:TAsignacion,id_asignacion',
            'criterios' => 'required|array',
        ]);
    
        $evaluacionExistente = Evaluacion::where('id_asignacion', $request->id_asignacion)->first();
    
        if ($evaluacionExistente) {
            return redirect()->route('evaluaciones.show', $request->id_asignacion)
                             ->withErrors(['Ya existe una evaluación para esta asignación.']);
        }
    
        $evaluacion = Evaluacion::create([
            'id_evaluacion' => 'E' . time(),
            'id_asignacion' => $request->id_asignacion,
            'id_supervisor' => Auth::user()->id_usuario,
            'id_docente' => Asignacion::find($request->id_asignacion)->id_docente,
            'id_semestre' => Asignacion::find($request->id_asignacion)->id_semestre,
            'fecha_evaluacion' => now(),
        ]);
    
        foreach ($request->criterios as $idCriterio => $cumple) {
            $evaluacion->detalles()->create([
                'id_criterio' => $idCriterio,
                'cumple' => $cumple,
                'comentario' => $request->comentarios[$idCriterio] ?? null,
            ]);
        }
    
        return redirect()->route('evaluaciones.show', $request->id_asignacion)
                         ->with('mensaje', 'Evaluación creada exitosamente.');
    }
}
