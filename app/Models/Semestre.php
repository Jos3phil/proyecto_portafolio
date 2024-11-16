<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    use HasFactory;
    protected $table = 'TSemestre';
    protected $primaryKey = 'id_semestre';
    public $incrementing = false;
    protected $fillable = ['id_semestre', 'nombre_semestre', 'fecha_inicio', 'fecha_fin'];

    // Relaciones
    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'id_semestre', 'id_semestre');
    }
}
