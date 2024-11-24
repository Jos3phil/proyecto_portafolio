<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;
    protected $table = 'TAsignacion';
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
    // Función para generar el identificador único
    public static function generateId()
    {
        $lastAsignacion = self::orderBy('id_asignacion', 'desc')->first();
        if (!$lastAsignacion) {
            $number = 1;
        } else {
            $number = intval(substr($lastAsignacion->id_asignacion, 2)) + 1;
        }
        return 'AS' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    // Evento creating para asignar el identificador único
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_asignacion = self::generateId();
        });
    }
}
