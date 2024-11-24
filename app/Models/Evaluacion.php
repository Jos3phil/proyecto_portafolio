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
        'id_supervisor',
        'id_docente',
        'id_semestre',
        'fecha_evaluacion',
    ];

    // Relaciones
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion', 'id_asignacion');
    }
    // Relación con el modelo Usuario para el supervisor
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'id_supervisor', 'id_usuario');
    }

    // Relación con el modelo Usuario para el docente
    public function docente()
    {
        return $this->belongsTo(User::class, 'id_docente', 'id_usuario');
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
}
