<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;
    protected $table = 'Asignacion';
    protected $primaryKey = 'id_asignacion';
    public $incrementing = false;
    protected $fillable = ['id_asignacion', 'id_supervisor', 'id_docente', 'id_semestre'];

    // Relaciones
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'id_supervisor', 'id_usuario');
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'id_docente', 'id_usuario');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'id_semestre', 'id_semestre');
    }
    
}
