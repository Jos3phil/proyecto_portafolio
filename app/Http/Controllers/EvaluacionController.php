<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CriterioEvaluacion;
use App\Models\SeccionEvaluacion;
use App\Models\Evaluacion;
use App\Models\Asignacion;
use Illuminate\Support\Facades\Auth;
use App\Models\DetalleEvaluacion;

class EvaluacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:SUPERVISOR']);
    }
 
    public function create(Request $request)
    {
        // Obtener el ID de la asignación desde la solicitud
        $idAsignacion = $request->input('id_asignacion');

        // Validar que el ID de la asignación está presente
        if (!$idAsignacion) {
            return redirect()->back()->withErrors(['ID de asignación no proporcionado.']);
        }

        // Recuperar la asignación con sus relaciones necesarias
        $asignacion = Asignacion::with('semestre')->find($idAsignacion);

        // Verificar que la asignación existe
        if (!$asignacion) {
            return redirect()->back()->withErrors(['Asignación no encontrada.']);
        }

        // Asegúrate de que estás recibiendo 'tipo_curso' o estableces un valor predeterminado
        $tipoCurso = $request->tipo_curso ?? 'TEORIA';
        $criterios = CriterioEvaluacion::where(function($query) use ($tipoCurso) {
                            $query->where('tipo_curso', $tipoCurso)
                                ->orWhere('tipo_curso', 'AMBOS');
                        })
                        ->with('seccion')
                        ->get()
                        ->groupBy('seccion.nombre_seccion');

        // Pasar todas las variables necesarias a la vista
        return view('evaluaciones.create', compact('criterios', 'tipoCurso', 'idAsignacion', 'asignacion'));
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
        // Obtener la asignación con sus relaciones
        $asignacion = Asignacion::with(['supervisor', 'docente', 'semestre'])->findOrFail($idAsignacion);

        // Obtener todas las evaluaciones de esta asignación, ordenadas por fecha
        $evaluaciones = Evaluacion::where('id_asignacion', $idAsignacion)
                                ->with('detalles.criterio')
                                ->orderBy('fecha_evaluacion', 'asc') // O 'created_at' si usas esa columna
                                ->get();

        return view('evaluaciones.show', compact('asignacion', 'evaluaciones'));  
        
    }
   
    public function detail($idEvaluacion)
    {
        $evaluacion = Evaluacion::with(['asignacion.docente', 'asignacion.supervisor', 'detalles.criterio'])->findOrFail($idEvaluacion);

        // Obtener la fecha límite del semestre
        $fechaFin = $evaluacion->asignacion->semestre->fecha_fin;
        $fechaActual = now();

        // Determinar si la fecha límite ha pasado
        $fechaVencida = $fechaActual->gt($fechaFin);

        // Obtener los criterios obligatorios
        $criteriosObligatorios = CriterioEvaluacion::where('obligatoriedad', true)
            ->where(function($query) use ($evaluacion) {
                $query->where('tipo_curso', $evaluacion->tipo_curso)
                    ->orWhere('tipo_curso', 'AMBOS');
            })
            ->pluck('id_criterio')
            ->toArray();

        // Criterios cumplidos en esta evaluación
        $criteriosCumplidos = $evaluacion->detalles()
            ->where('cumple', true)
            ->pluck('id_criterio')
            ->toArray();

        // Criterios faltantes en esta evaluación
        $faltantes = array_diff($criteriosObligatorios, $criteriosCumplidos);

        return view('evaluaciones.detail', compact('evaluacion', 'fechaVencida', 'faltantes', 'fechaFin'));
    }
    public function store(Request $request)
    {
        return $this->storeEvaluation($request);
    }
    public function storeEvaluation(Request $request)
    {
        $request->validate([
            'id_asignacion' => 'required|exists:TAsignacion,id_asignacion',
            'tipo_curso' => 'required|in:TEORIA,PRACTICA',
            'criterios' => 'required|array',
        ]);
        /*
        $criteriosObligatorios = CriterioEvaluacion::where('obligatoriedad', true)
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
            */
            // Obtener la asignación
            $asignacion = Asignacion::with('semestre')->findOrFail($request->id_asignacion);

        // Generar un ID único para la evaluación
        $idEvaluacion = 'E' . time();
        $fechaFin = $asignacion->semestre->fecha_fin;

         // Obtener la fecha actual
        $fechaActual = now();

        // Obtener los criterios obligatorios
        $criteriosObligatorios = CriterioEvaluacion::where('obligatoriedad', true)
        ->where(function($query) use ($request) {
            $query->where('tipo_curso', $request->tipo_curso)
                ->orWhere('tipo_curso', 'AMBOS');
        })
        ->pluck('id_criterio')
        ->toArray();

        // Criterios seleccionados en esta evaluación
        $criteriosSeleccionados = array_keys($request->criterios);

        // Criterios cumplidos hasta ahora (incluyendo evaluaciones anteriores)
        $criteriosCumplidos = DetalleEvaluacion::whereIn('id_evaluacion', function($query) use ($request) {
                $query->select('id_evaluacion')
                    ->from('TEvaluacion')
                    ->where('id_asignacion', $request->id_asignacion)
                    ->where('tipo_curso', $request->tipo_curso);
            })
            ->where('cumple', true)
            ->pluck('id_criterio')
            ->toArray();

        // Total de criterios cumplidos incluyendo los de esta evaluación
        $criteriosTotalCumplidos = array_unique(array_merge($criteriosCumplidos, $criteriosSeleccionados));

        // Faltantes por cumplir
        $faltantes = array_diff($criteriosObligatorios, $criteriosTotalCumplidos);

        // Si la fecha actual ha pasado la fecha_fin, exigir que no queden faltantes
        if ($fechaActual->gt($fechaFin) && count($faltantes) > 0) {
            return redirect()->back()->withErrors(['Debe evaluar todos los criterios obligatorios antes de la fecha límite.'])->withInput();
        }
        // Crear la nueva evaluación
        $idEvaluacion = 'E' . time();

        $evaluacion = Evaluacion::create([
            'id_evaluacion' => $idEvaluacion,
            'id_asignacion' => $request->id_asignacion,
            'id_semestre' => $asignacion->id_semestre,
            'fecha_evaluacion' => $fechaActual,
            'tipo_curso' => $request->tipo_curso,
        ]);
        
        foreach ($request->criterios as $idCriterio => $cumple) {
            $evaluacion->detalles()->create([
                'id_criterio' => $idCriterio,
                'cumple' => $cumple,
                'comentario' => $request->comentarios[$idCriterio] ?? null,
            ]);
        }

        // Calcular el progreso total
        $progreso = $evaluacion->calcularProgresoTotal();

        // Actualizar el progreso en la evaluación (si es necesario)
        $evaluacion->progreso = $progreso;
        $evaluacion->save();

        return redirect()->route('evaluaciones.show', $request->id_asignacion)
                         ->with('mensaje', 'Evaluación creada exitosamente.');
    }
    public function continueEvaluation($idEvaluacion)
    {
        // Obtener la evaluación anterior
        $evaluacionAnterior = Evaluacion::with('detalles')->findOrFail($idEvaluacion);

        $asignacion = $evaluacionAnterior->asignacion;
        $fechaFin = $asignacion->semestre->fecha_fin;
        
        // Definir $idAsignacion a partir de la asignación
        $idAsignacion = $asignacion->id_asignacion;
         // Obtener los criterios necesarios
        $tipoCurso = $evaluacionAnterior->tipo_curso;

         // **Nuevo código**: Obtener los IDs de todas las evaluaciones anteriores para la misma asignación y tipo de curso
        $evaluacionesAnteriores = Evaluacion::where('id_asignacion', $idAsignacion)
        ->where('tipo_curso', $tipoCurso)
        ->pluck('id_evaluacion');

         // Obtener los criterios ya evaluados en evaluaciones anteriores
        $criteriosEvaluados = DetalleEvaluacion::whereIn('id_evaluacion', $evaluacionesAnteriores)
        ->pluck('id_criterio')
        ->toArray();

        $criterios = CriterioEvaluacion::where(function($query) use ($tipoCurso) {
                        $query->where('tipo_curso', $tipoCurso)
                              ->orWhere('tipo_curso', 'AMBOS');
                    })
                    ->with('seccion')
                    ->get()
                    ->groupBy('seccion.nombre_seccion');

        return view('evaluaciones.continue', compact('criterios', 'tipoCurso', 'idAsignacion', 'evaluacionAnterior', 'criteriosEvaluados', 'fechaFin'));
    }
   
    public function storeContinuation(Request $request)
    {
        $request->validate([
            'id_asignacion' => 'required|exists:TAsignacion,id_asignacion',
            'tipo_curso' => 'required|in:TEORIA,PRACTICA',
            'criterios' => 'required|array',
            'id_evaluacion_anterior' => 'required|exists:TEvaluacion,id_evaluacion',
        ]);

        $evaluacionAnterior = Evaluacion::findOrFail($request->id_evaluacion_anterior);
        $asignacion = $evaluacionAnterior->asignacion;
        $fechaFin = $asignacion->semestre->fecha_fin;

        // Verificar si la fecha límite ha pasado
        if (now()->gt($fechaFin)) {
            return redirect()->back()->withErrors(['La fecha límite para completar los criterios obligatorios ha expirado.'])->withInput();
        }
        // Obtener los criterios obligatorios
        $criteriosObligatorios = CriterioEvaluacion::where('obligatoriedad', true)
            ->where(function($query) use ($request) {
                $query->where('tipo_curso', $request->tipo_curso)
                    ->orWhere('tipo_curso', 'AMBOS');
            })
            ->pluck('id_criterio')
            ->toArray();

        
        // Obtener los criterios obligatorios
        $criteriosObligatorios = CriterioEvaluacion::where('obligatoriedad', true)
        ->where(function($query) use ($request) {
            $query->where('tipo_curso', $request->tipo_curso)
                ->orWhere('tipo_curso', 'AMBOS');
        })
        ->pluck('id_criterio')
        ->toArray();
        // Criterios cumplidos hasta ahora (incluyendo evaluaciones anteriores)
        $criteriosCumplidos = DetalleEvaluacion::whereIn('id_evaluacion', function($query) use ($request) {
            $query->select('id_evaluacion')
                ->from('TEvaluacion')
                ->where('id_asignacion', $request->id_asignacion)
                ->where('tipo_curso', $request->tipo_curso);
        })
            ->where('cumple', true)
            ->pluck('id_criterio')
            ->toArray();
        // Criterios seleccionados en esta evaluación
        $criteriosSeleccionados = array_keys($request->criterios);

        // Total de criterios cumplidos
        $criteriosTotalCumplidos = array_unique(array_merge($criteriosCumplidos, $criteriosSeleccionados));

        // Faltantes por cumplir
        $faltantes = array_diff($criteriosObligatorios, $criteriosTotalCumplidos);

        // Si la fecha actual ha pasado la fecha_fin y faltan criterios obligatorios
        if (now()->gt($fechaFin) && count($faltantes) > 0) {
            return redirect()->back()->withErrors(['Debe evaluar todos los criterios obligatorios antes de la fecha límite.'])->withInput();
        }

        // Crear la nueva evaluación
        $idEvaluacion = 'E' . time();

        $evaluacion = Evaluacion::create([
            'id_evaluacion' => $idEvaluacion,
            'id_asignacion' => $request->id_asignacion,
            'id_semestre' => $asignacion->id_semestre,
            'fecha_evaluacion' => now(),
            'tipo_curso' => $request->tipo_curso,
        ]);

        // Guardar los detalles de la evaluación
        foreach ($request->criterios as $idCriterio => $cumple) {
            $evaluacion->detalles()->create([
                'id_criterio' => $idCriterio,
                'cumple' => $cumple,
                'comentario' => $request->comentarios[$idCriterio] ?? null,
            ]);
        }

        // Calcular el progreso total
        $progreso = $evaluacion->calcularProgresoTotal();

        // Actualizar el progreso en la evaluación (si es necesario)
        $evaluacion->progreso = $progreso;
        $evaluacion->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('evaluaciones.show', $request->id_asignacion)
                        ->with('mensaje', 'Evaluación continuada creada exitosamente.');
    }
    public function destroy($idEvaluacion)
    {
        $evaluacion = Evaluacion::findOrFail($idEvaluacion);
        $idAsignacion = $evaluacion->id_asignacion;

        // Eliminar los detalles asociados
        $evaluacion->detalles()->delete();

        // Eliminar la evaluación
        $evaluacion->delete();

        return redirect()->route('evaluaciones.show', $idAsignacion)
                        ->with('mensaje', 'Evaluación eliminada exitosamente.');
    }
}
