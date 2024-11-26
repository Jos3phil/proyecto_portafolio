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
        // Obtener todas las evaluaciones de la asignación y tipo de curso
        $evaluaciones = Evaluacion::where('id_asignacion', $this->id_asignacion)
            ->where('tipo_curso', $this->tipo_curso)
            ->with('detalles')
            ->get();

        // Obtener todos los criterios correspondientes
        $criterios = CriterioEvaluacion::where(function($query) {
            $query->where('tipo_curso', $this->tipo_curso)
                ->orWhere('tipo_curso', 'AMBOS');
        })->get();

        $pesoTotal = $criterios->sum('peso');

        // Criterios cumplidos en todas las evaluaciones
        $criteriosCumplidosIds = [];

        foreach ($evaluaciones as $evaluacion) {
            foreach ($evaluacion->detalles as $detalle) {
                if ($detalle->cumple && !in_array($detalle->id_criterio, $criteriosCumplidosIds)) {
                    $criteriosCumplidosIds[] = $detalle->id_criterio;
                }
            }
        }

        $pesoCumplido = $criterios->whereIn('id_criterio', $criteriosCumplidosIds)->sum('peso');

        $progreso = ($pesoCumplido / $pesoTotal) * 100;

        return round($progreso, 2);
    }
}
