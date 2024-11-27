<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;
    protected $table = 'TEvaluacion';
    protected $primaryKey = 'id_evaluacion';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_evaluacion',
        'id_asignacion',
        'id_semestre',
        'tipo_curso', 
        'fecha_evaluacion',
        'progreso',
    ];

    // Relaciones
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion', 'id_asignacion');
    }
   
   
    // Relación con el modelo Semestre
    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'id_semestre', 'id_semestre');
    }

    // Relación con el modelo DetalleEvaluacion
    public function detalles()
    {
        return $this->hasMany(DetalleEvaluacion::class, 'id_evaluacion', 'id_evaluacion');
    }
     // Método para calcular el progreso
     public function calcularProgreso()
     {
         // Obtener todos los criterios correspondientes al tipo de curso
         $criterios = CriterioEvaluacion::where(function($query) {
                             $query->where('tipo_curso', $this->tipo_curso)
                                   ->orWhere('tipo_curso', 'AMBOS');
                         })->get();
 
         $pesoTotal = $criterios->sum('peso');
 
         // Obtener los detalles de la evaluación
         $detalles = $this->detalles;
 
         // Calcular el peso acumulado
         $pesoCumplido = 0;
         foreach ($detalles as $detalle) {
             $criterio = $criterios->where('id_criterio', $detalle->id_criterio)->first();
             if ($detalle->cumple && $criterio) {
                 $pesoCumplido += $criterio->peso;
             }
         }
 
         // Calcular el porcentaje de progreso
         $progreso = ($pesoCumplido / $pesoTotal) * 100;
 
         return round($progreso, 2); // Redondear a 2 decimales
     }
    
    public function calcularProgresoTotal()
    {
        /*
        Obtener los criterios correspondientes: Se obtienen todos los criterios que aplican al tipo de curso o a "AMBOS".
        Calcular el peso total: Se suma el peso de todos los criterios obtenidos.
        Obtener todas las evaluaciones anteriores: Se obtienen todas las evaluaciones para la asignación y tipo de curso específicos.
        Obtener los detalles de las evaluaciones: Se obtienen todos los DetalleEvaluacion asociados a las evaluaciones anteriores.
        Obtener los IDs únicos de los criterios evaluados: Se extraen los IDs de criterios evaluados sin duplicados.
        Calcular el peso cumplido: Se suma el peso de los criterios evaluados.
        Calcular el progreso: Se calcula el porcentaje del peso cumplido respecto al peso total.
        */
        $idAsignacion = $this->id_asignacion;
        $tipoCurso = $this->tipo_curso;
        // Obtener todas las evaluaciones de la asignación y tipo de curso
        $evaluaciones = Evaluacion::where('id_asignacion', $idAsignacion)
            ->where('tipo_curso',$tipoCurso)
            ->where('fecha_evaluacion', '<=', $this->fecha_evaluacion)
            ->pluck('id_evaluacion');

        // Obtener todos los criterios correspondientes
        $criterios = CriterioEvaluacion::where(function($query) use ($tipoCurso) {
            $query->where('tipo_curso', $tipoCurso)
                ->orWhere('tipo_curso', 'AMBOS');
        })->get();

        $totalPeso = $criterios->sum('peso');

        // Obtener los detalles de todas las evaluaciones
        $detalles = DetalleEvaluacion::whereIn('id_evaluacion', $evaluaciones)->get();

        // Obtener los IDs únicos de los criterios evaluados que cumplen
        $criteriosCumplidosIds = DetalleEvaluacion::whereIn('id_evaluacion', $evaluaciones)
        ->pluck('id_criterio')
        ->unique()
        ->toArray();
        // Sumar el peso de los criterios cumplidos
        $pesoCumplido = $criterios->whereIn('id_criterio', $criteriosCumplidosIds)->sum('peso');

        // Calcular el progreso
        $progreso = ($totalPeso > 0) ? ($pesoCumplido / $totalPeso) * 100:0;

        return round($progreso, 2);
    }
}
