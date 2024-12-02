<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CriterioEvaluacion;
use App\Models\SeccionEvaluacion;
use App\Models\Evaluacion;
use App\Models\Asignacion;
use Illuminate\Support\Facades\Auth;
use App\Models\DetalleEvaluacion;
use App\Models\User;

class EvaluacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // Opcionalmente, puedes usar middleware específicos para roles
        // $this->middleware('role:ADMIN|SUPERVISOR|DOCENTE');    
    }
 
    public function create(Request $request)
    {
        $user = Auth::User();

        if (!$user->hasAnyRole(['ADMINISTRADOR', 'SUPERVISOR'])) {
            abort(403, 'No tienes permisos para crear evaluaciones.');
        }
        
        // Obtener el ID de la asignación desde la solicitud
        $idAsignacion = $request->input('id_asignacion');

        // Validar que el ID de la asignación está presente
        if (!$idAsignacion) {
            return redirect()->back()->withErrors(['ID de asignación no proporcionado.']);
        }

        $asignacion = Asignacion::with(['docente', 'semestre', 'supervisor'])
                        ->where('id_asignacion', $idAsignacion)
                        ->first();

        // Verificar que la asignación existe
        if (!$asignacion) {
            return redirect()->back()->withErrors(['Asignación no encontrada.']);
        }

        // Si el usuario es Supervisor, verificar que la asignación pertenece a él
        if ($user->hasRole('SUPERVISOR')) {
            $asignacion = Asignacion::where('id_asignacion', $idAsignacion)
                                    ->where('id_supervisor', $user->id_usuario)
                                    ->with('semestre')
                                    ->first();

            if (!$asignacion) {
                return redirect()->back()->withErrors(['Asignación no encontrada o no pertenece al supervisor actual.']);
            }
        } elseif ($user->hasRole('ADMINISTRADOR')) {
            // Admin puede acceder a cualquier asignación
            $asignacion = Asignacion::with('semestre')->find($idAsignacion);

            if (!$asignacion) {
                return redirect()->back()->withErrors(['Asignación no encontrada.']);
            }
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
        // Obtener los semestres disponibles
        $semestres = $asignacion->semestre->pluck('nombre_semestre', 'id_semestre');
        // Pasar todas las variables necesarias a la vista
        return view('evaluaciones.create', compact('criterios', 'tipoCurso', 'idAsignacion', 'asignacion','semestres'));
    }

    public function index()
    {
        $user = Auth::User();
        // Depuración: Verificar roles
        //dd($user->roles->pluck('tipo_rol'));
        if ($user->hasRole('ADMINISTRADOR')) {
            // Administrador: ver todas las evaluaciones
            $evaluaciones = Evaluacion::all();

        } elseif ($user->hasRole('SUPERVISOR')) {
            // Supervisor: ver evaluaciones de sus docentes asignados
            $asignaciones = Asignacion::where('id_supervisor', $user->id_usuario)
                                       ->pluck('id_asignacion');
    
            $evaluaciones = Evaluacion::whereIn('id_asignacion', $asignaciones)
                                       ->with([
                                           'asignacion.supervisor',
                                           'asignacion.docente',
                                           'semestre',
                                       ])
                                       ->get();
        } elseif ($user->hasRole('DOCENTE')) {
            // Docente: ver solo sus propias evaluaciones
            $asignaciones = Asignacion::where('id_docente', $user->id_usuario)
                                       ->pluck('id_asignacion');
    
            $evaluaciones = Evaluacion::whereIn('id_asignacion', $asignaciones)
                                       ->with([
                                           'asignacion.supervisor',
                                           'asignacion.docente',
                                           'semestre',
                                       ])
                                       ->get();
        } else {
            // Otros roles: denegar acceso o manejar según necesidad
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

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
    public function show($idEvaluacion)
    {
        $user = Auth::User();
       
        // Obtener la evaluación con sus relaciones
        $evaluacion = Evaluacion::with([
            'asignacion.supervisor',
            'asignacion.docente',
            'detalles.criterio',
            'semestre'
        ])->findOrFail($idEvaluacion);

        // Verificar permisos según el rol del usuario
        if ($user->hasRole('ADMIN')) {
            // Administrador puede ver cualquier evaluación
        } elseif ($user->hasRole('SUPERVISOR')) {
            // Supervisor solo puede ver evaluaciones de sus docentes asignados
            if ($evaluacion->asignacion->id_supervisor !== $user->id_usuario) {
                abort(403, 'No tienes permisos para ver esta evaluación.');
            }
        } elseif ($user->hasRole('DOCENTE')) {
            // Docente solo puede ver sus propias evaluaciones
            if ($evaluacion->asignacion->id_docente !== $user->id_usuario) {
                abort(403, 'No tienes permisos para ver esta evaluación.');
            }
        } else {
            abort(403, 'No tienes permisos para ver esta evaluación.');
        }

        return view('evaluaciones.show', compact('evaluacion'));
        
    }
   
    public function detail($idEvaluacion)
    {
        $user = Auth::user();
        $evaluacion = Evaluacion::with(['asignacion.docente', 'asignacion.supervisor', 'detalles.criterio'])->findOrFail($idEvaluacion);

        // Verificar permisos según rol
        if ($user->hasRole('ADMIN')) {
            // Administrador puede ver cualquier detalle
        } elseif ($user->hasRole('SUPERVISOR')) {
            // Supervisor solo puede ver detalles de sus docentes asignados
            if ($evaluacion->asignacion->id_supervisor !== $user->id_usuario) {
                abort(403, 'No tienes permisos para ver esta evaluación.');
            }
        } elseif ($user->hasRole('DOCENTE')) {
            // Docente solo puede ver sus propias evaluaciones
            if ($evaluacion->asignacion->id_docente !== $user->id_usuario) {
                abort(403, 'No tienes permisos para ver esta evaluación.');
            }
        } else {
            abort(403, 'No tienes permisos para ver esta evaluación.');
        }
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
        $user = Auth::user();

        if (!$user->hasAnyRole(['ADMINISTRADOR', 'SUPERVISOR'])) {
            abort(403, 'No tienes permisos para crear evaluaciones.');
        }

        return $this->storeEvaluation($request);
    }
    public function storeEvaluation(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_asignacion' => 'required|exists:TAsignacion,id_asignacion',
            'tipo_curso' => 'required|in:TEORIA,PRACTICA',
            'criterios' => 'required|array',
        ]);
        $idAsignacion = $request->input('id_asignacion');
        $tipoCurso = $request->input('tipo_curso');

        // Verificar asignación según rol
        if ($user->hasRole('SUPERVISOR')) {
            $asignacion = Asignacion::where('id_asignacion', $idAsignacion)
                                    ->where('id_supervisor', $user->id_usuario)
                                    ->with('semestre')
                                    ->first();

            if (!$asignacion) {
                return redirect()->back()->withErrors(['Asignación no encontrada o no pertenece al supervisor.']);
            }
        } elseif ($user->hasRole('ADMINISTRADOR')) {
            $asignacion = Asignacion::with('semestre')->findOrFail($idAsignacion);
        }
    
        $idSemestre = $asignacion->id_semestre;

        // Obtener la asignación con el semestre
        $idEvaluacion = 'E' . time();
        $fechaFin = $asignacion->semestre->fecha_fin;
        $fechaActual = now();

        // Obtener los criterios obligatorios
        $criteriosObligatorios = CriterioEvaluacion::where('obligatoriedad', true)
        ->where(function($query) use ($tipoCurso) {
            $query->where('tipo_curso', $tipoCurso)
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
            //return redirect()->back()->withErrors(['Debe evaluar todos los criterios obligatorios antes de la fecha límite.'])->withInput();
            $mensajeError = 'La fecha límite para completar los criterios obligatorios ha expirado y no se han evaluado todos los criterios obligatorios.';

            // Reobtener los criterios para la vista
            $criterios = CriterioEvaluacion::where(function($query) use ($tipoCurso) {
                    $query->where('tipo_curso', $tipoCurso)
                        ->orWhere('tipo_curso', 'AMBOS');
                })
                ->with('seccion')
                ->get()
                ->groupBy('seccion.nombre_seccion');

            // Devolver a la vista 'create' con errores y variables necesarias
            return redirect()->back()
                ->withErrors([$mensajeError])
                ->withInput($request->all())
                ->with([
                    'asignacion' => $asignacion,
                    'criterios' => $criterios,
                    'tipoCurso' => $tipoCurso,
                    'idAsignacion' => $idAsignacion,
                ]);
        }
        // Crear la nueva evaluación
        $idEvaluacion = 'E' . time();

        $evaluacion = Evaluacion::create([
            'id_evaluacion' => $idEvaluacion,
            'id_asignacion' => $idAsignacion,
            'id_semestre' => $idSemestre,
            'tipo_curso' => $tipoCurso,
            'fecha_evaluacion' => now(),
        ]);
        
        // Guardar los detalles de la evaluación
        foreach ($criteriosSeleccionados as $idCriterio) {
            DetalleEvaluacion::create([
                'id_evaluacion' => $idEvaluacion,
                'id_criterio' => $idCriterio,
                'cumple' => $request->criterios[$idCriterio] ?? false,
                'comentario' => $request->comentarios[$idCriterio] ?? null,
            ]);
        }

        // Calcular el progreso total
        //$progreso = $evaluacion->calcularProgresoTotal();

        // Actualizar el progreso en la evaluación (si es necesario)
        //$evaluacion->progreso = $progreso;
        //$evaluacion->save();

        return redirect()->route('evaluaciones.show', ['idEvaluacion' => $evaluacion->id_evaluacion])
                         ->with('mensaje', 'Evaluación creada exitosamente.');
    }
    public function continueEvaluation($idEvaluacion)
    {
        $user = Auth::user();
        // Obtener la evaluación anterior
        $evaluacionAnterior = Evaluacion::with(['asignacion.supervisor','asignacion.docente'])->findOrFail($idEvaluacion);

        // Verificar permisos según rol
        if ($user->hasRole('ADMINISTRADOR')) {
            // Administrador puede continuar cualquier evaluación
        } elseif ($user->hasRole('SUPERVISOR')) {
            // Supervisor solo puede continuar evaluaciones de sus docentes asignados
            if ($evaluacionAnterior->asignacion->id_supervisor !== $user->id_usuario) {
                abort(403, 'No tienes permisos para continuar esta evaluación.');
            }
        } else {
            abort(403, 'No tienes permisos para continuar esta evaluación.');
        }

        $asignacion = $evaluacionAnterior->asignacion;
        $fechaFin = $asignacion->semestre->fecha_fin;
        
        // Definir $idAsignacion a partir deS la asignación
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
        $user = Auth::user();

        if (!$user->hasAnyRole(['ADMINISTRADOR', 'SUPERVISOR'])) {
            abort(403, 'No tienes permisos para continuar evaluaciones.');
        }

        $request->validate([
            'id_asignacion' => 'required|exists:TAsignacion,id_asignacion',
            'tipo_curso' => 'required|in:TEORIA,PRACTICA',
            'criterios' => 'required|array',
            'id_evaluacion_anterior' => 'required|exists:TEvaluacion,id_evaluacion',
        ]);

        $idAsignacion = $request->input('id_asignacion');
        $tipoCurso = $request->input('tipo_curso');

        $evaluacionAnterior = Evaluacion::with(['asignacion.supervisor'])->findOrFail($request->input('id_evaluacion_anterior'));

        $asignacion = $evaluacionAnterior->asignacion;
         // Verificar permisos según rol
        if ($user->hasRole('SUPERVISOR') && $asignacion->supervisor_id !== $user->id_usuario) {
            abort(403, 'No tienes permisos para continuar esta evaluación.');
        }
        $idSemestre = $asignacion->id_semestre;
        $fechaFin = $asignacion->semestre->fecha_fin;
        $fechaActual = now();

        // Verificar si la fecha límite ha pasado
        if ($fechaActual->gt($fechaFin)) {
            return redirect()->back()->withErrors(['La fecha límite para completar los criterios obligatorios ha expirado.'])->withInput();
        }
        // Obtener los criterios obligatorios
        $criteriosObligatorios = CriterioEvaluacion::where('obligatoriedad', true)
            ->where(function($query) use ($tipoCurso) {
                $query->where('tipo_curso', $tipoCurso)
                    ->orWhere('tipo_curso', 'AMBOS');
            })
            ->pluck('id_criterio')
            ->toArray();

        // Obtener los IDs de todas las evaluaciones anteriores para la misma asignación y tipo de curso
        $evaluacionesAnteriores = Evaluacion::where('id_asignacion', $idAsignacion)
                                            ->where('tipo_curso', $tipoCurso)
                                            ->pluck('id_evaluacion');

        // Obtener los criterios ya evaluados en evaluaciones anteriores
        $criteriosEvaluados = DetalleEvaluacion::whereIn('id_evaluacion', $evaluacionesAnteriores)
                                                ->pluck('id_criterio')
                                                ->toArray();
        // Criterios seleccionados en esta evaluación
        $criteriosSeleccionados = array_keys($request->criterios);
        
         // Verificar que no se estén evaluando criterios ya evaluados
        $criteriosDuplicados = array_intersect($criteriosEvaluados, $criteriosSeleccionados);
        if (count($criteriosDuplicados) > 0) {
            $mensajeError = 'Algunos criterios ya han sido evaluados previamente.';
    
            // Reobtener los criterios para la vista
            $criterios = CriterioEvaluacion::where(function($query) use ($tipoCurso) {
                    $query->where('tipo_curso', $tipoCurso)
                          ->orWhere('tipo_curso', 'AMBOS');
                })
                ->with('seccion')
                ->get()
                ->groupBy('seccion.nombre_seccion');
    
            return redirect()->back()
                ->withErrors([$mensajeError])
                ->withInput($request->all())
                ->with([
                    'criterios' => $criterios,
                    'tipoCurso' => $tipoCurso,
                    'idAsignacion' => $idAsignacion,
                    'evaluacionAnterior' => $evaluacionAnterior,
                    'criteriosEvaluados' => $criteriosEvaluados,
                    'fechaFin' => $fechaFin,
                ]);
        }
        // Total de criterios cumplidos
        $criteriosTotalCumplidos = array_unique(array_merge($criteriosEvaluados, $criteriosSeleccionados));

        // Faltantes por cumplir
        $faltantes = array_diff($criteriosObligatorios, $criteriosTotalCumplidos);

        // Si la fecha actual ha pasado la fecha_fin y faltan criterios obligatorios
        if ($fechaActual->gt($fechaFin) && count($faltantes) > 0) {
            $mensajeError = 'La fecha límite para completar los criterios obligatorios ha expirado y no se han evaluado todos los criterios obligatorios.';

            // Reobtener los criterios para la vista
            $criterios = CriterioEvaluacion::where(function($query) use ($tipoCurso) {
                    $query->where('tipo_curso', $tipoCurso)
                          ->orWhere('tipo_curso', 'AMBOS');
                })
                ->with('seccion')
                ->get()
                ->groupBy('seccion.nombre_seccion');
    
            return redirect()->back()
                ->withErrors([$mensajeError])
                ->withInput($request->all())
                ->with([
                    'criterios' => $criterios,
                    'tipoCurso' => $tipoCurso,
                    'idAsignacion' => $idAsignacion,
                    'evaluacionAnterior' => $evaluacionAnterior,
                    'criteriosEvaluados' => $criteriosEvaluados,
                    'fechaFin' => $fechaFin,
                ]);
            }
            // Crear nueva evaluación de continuación
            $evaluacion = Evaluacion::create([
                'id_evaluacion' => 'E' . time(),
                'id_asignacion' => $idAsignacion,
                'id_semestre' => $idSemestre, 
                'tipo_curso' => $tipoCurso,
                'fecha_evaluacion' => now(),
            ]);

        // Guardar los detalles de la evaluación
        foreach ($criteriosSeleccionados as $idCriterio) {
            DetalleEvaluacion::create([
                'id_evaluacion' => $evaluacion->id_evaluacion,
                'id_criterio' => $idCriterio,
                'cumple' => true, // Agregar este campo
                'comentario' => $request->comentarios[$idCriterio] ?? null,
            ]);
        }

         // Calcular el progreso total
        $progresoTotal = $evaluacion->calcularProgresoTotal();

        // Actualizar el progreso en la evaluación actual
        $evaluacion->progreso = $progresoTotal;
        $evaluacion->save();

        return redirect()->route('evaluaciones.show', ['idEvaluacion' => $evaluacion->id_evaluacion])
                 ->with('mensaje', 'Evaluación continuada exitosamente.');
    }
    public function destroy($idEvaluacion)
    {
        $user = Auth::user();

        // Obtener la evaluación
        $evaluacion = Evaluacion::with('asignacion')->findOrFail($idEvaluacion);
        // Verificar permisos según rol
        if ($user->hasRole('ADMIN')) {
            // Administrador puede eliminar cualquier evaluación
        } elseif ($user->hasRole('SUPERVISOR')) {
            // Supervisor solo puede eliminar evaluaciones de sus docentes asignados
            if ($evaluacion->asignacion->supervisor_id !== $user->id_usuario) {
                abort(403, 'No tienes permisos para eliminar esta evaluación.');
            }
        } else {
            // Otros roles no tienen permiso
            abort(403, 'No tienes permisos para eliminar esta evaluación.');
        }

        $idAsignacion = $evaluacion->id_asignacion;

        // Eliminar los detalles asociados
        $evaluacion->detalles()->delete();

        // Eliminar la evaluación
        $evaluacion->delete();

        return redirect()->route('evaluaciones.index')
                        ->with('mensaje', 'Evaluación eliminada exitosamente.');
    }
}
