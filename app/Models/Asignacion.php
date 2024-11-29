<?php
/**
 * Class Asignacion
 *
 * This model represents the 'TAsignacion' table in the database.
 * It includes relationships to the User and Semestre models.
 * 
 * @package App\Models
 * 
 * @property string $id_asignacion
 * @property string $id_supervisor
 * @property string $id_docente
 * @property string $id_semestre
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Asignacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asignacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asignacion query()
 * 
 * @mixin \Eloquent
 * 
 * @method static string generateId() Generates a unique identifier for the 'id_asignacion' field.
 * 
 * Relationships:
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo supervisor() Defines a relationship to the User model as a supervisor.
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo docente() Defines a relationship to the User model as a docente.
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo semestre() Defines a relationship to the Semestre model.
 * 
 * Events:
 * @method static void boot() Overrides the boot method to assign a unique identifier when creating a new record.
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\semestre;
use App\Models\Evaluacion;

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
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'id_asignacion', 'id_asignacion');
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
