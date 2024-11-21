<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleEvaluacion extends Model
{
    use HasFactory;
    protected $table = 'TDetalleEvaluacion';
    protected $primaryKey = 'id_detalle';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id_evaluacion',
        'id_criterio',
        'cumple',
        'comentario',
    ];

    // Relación con el modelo Evaluacion
    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'id_evaluacion', 'id_evaluacion');
    }

    // Relación con el modelo CriterioEvaluacion
    public function criterio()
    {
        return $this->belongsTo(CriterioEvaluacion::class, 'id_criterio', 'id_criterio');
    }
}
