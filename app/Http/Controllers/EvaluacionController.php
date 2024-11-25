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
        $evaluaciones = Evaluacion::with([
            'asignacion.supervisor',
            'asignacion.docente',
            'semestre',
        ])->get();

        return view('evaluaciones.index', compact('evaluaciones'));
    }

    public function showEvaluationForm(Request $request)
    {
        $tipoCurso = $request->input('tipo_curso', 'TEORIA'); // Valor por defecto 'TEORIA'
        $idAsignacion = $request->input('id_asignacion');
        // Obtener criterios que aplican al tipo de curso o a ambos
         // Verificar que idAsignacion no sea nulo
        if (!$idAsignacion) {
            return redirect()->back()->withErrors(['No se proporcionó una asignación válida.']);
        }
        $criterios = CriterioEvaluacion::where('tipo_curso', $tipoCurso)
                    ->orWhere('tipo_curso', 'AMBOS')
                    ->with('seccion')
                    ->get()
                    ->groupBy('seccion.nombre_seccion');

        return view('evaluaciones.create', compact('criterios', 'tipoCurso','idAsignacion'));
    }
    public function show($idAsignacion)
    {
        $asignacion = Asignacion::with(['supervisor', 'docente', 'semestre'])->findOrFail($idAsignacion);
        $evaluaciones = Evaluacion::where('id_asignacion', $idAsignacion)->with('detalles.criterio')->get();

        return view('evaluaciones.show', compact('asignacion', 'evaluaciones'));    
       
    }

    public function storeEvaluation(Request $request)
    {
        $request->validate([
            'id_asignacion' => 'required|exists:TAsignacion,id_asignacion',
            'tipo_curso' => 'required|in:TEORIA,PRACTICA',
            'criterios' => 'required|array',
        ]);
        $criteriosObligatorios = CriterioEvaluacion::where('obligatorio', true)
                ->where(function($query) use ($request) {
                    $query->where('tipo_curso', $request->tipo_curso)
                        ->orWhere('tipo_curso', 'AMBOS');
                })
                ->pluck('id_criterio')->toArray();

        // Verificar que todos los criterios obligatorios han sido marcados
        $criteriosSeleccionados = array_keys($request->criterios);
        $faltantes = array_diff($criteriosObligatorios, $criteriosSeleccionados);

        if (count($faltantes) > 0) {
            return redirect()->back()->withErrors(['Debe evaluar todos los criterios obligatorios.'])->withInput();
        }
            // Obtener la asignación
        $asignacion = Asignacion::findOrFail($request->id_asignacion);

        // Generar un ID único para la evaluación
        $idEvaluacion = 'E' . time();
    
        $evaluacion = Evaluacion::create([
            'id_evaluacion' => $idEvaluacion,
            'id_asignacion' => $request->id_asignacion,
            'id_semestre' => $asignacion->id_semestre,
            'fecha_evaluacion' => now(),
            'tipo_curso' => $request->tipo_curso,
        ]);
    
        foreach ($request->criterios as $idCriterio => $cumple) {
            $evaluacion->detalles()->create([
                'id_criterio' => $idCriterio,
                'cumple' => $cumple,
                'comentario' => $request->comentarios[$idCriterio] ?? null,
            ]);
        }
        // Calcular el progreso
        $progreso = $this->calcularProgreso($evaluacion);
        return redirect()->route('evaluaciones.show', $request->id_asignacion)
                         ->with('mensaje', 'Evaluación creada exitosamente.');
    }
  
}
