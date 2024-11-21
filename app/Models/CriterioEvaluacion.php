<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriterioEvaluacion extends Model
{
    use HasFactory;
    protected $table = 'TCriteriosEvaluacion';
    protected $primaryKey = 'id_criterio';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'descripcion_criterio',
        'id_seccion',
        'obligatoriedad',
        'peso',
        'tipo_curso',
    ];

    public function seccion()
    {
        return $this->belongsTo(SeccionEvaluacion::class, 'id_seccion', 'id_seccion');
    }
}
