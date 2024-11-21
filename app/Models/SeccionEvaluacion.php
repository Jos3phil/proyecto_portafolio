<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeccionEvaluacion extends Model
{
    use HasFactory;
    
    protected $table = 'TSeccionesEvaluacion';
    protected $primaryKey = 'id_seccion';
    public $timestamps = true;

    protected $fillable = [
        'nombre_seccion',
        'descripcion_seccion',
    ];

    public function criterios()
    {
        return $this->hasMany(CriterioEvaluacion::class, 'id_seccion', 'id_seccion');
    }
}
