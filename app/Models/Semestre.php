<?php
/*
 *
 * Class Semestre
 *
 * This model represents the 'TSemestre' table in the database.
 * It includes the following properties:
 * - id_semestre: The primary key for the table.
 * - nombre_semestre: The name of the semester.
 * - fecha_inicio: The start date of the semester.
 * - fecha_fin: The end date of the semester.
 *
 * Relationships:
 * - asignaciones: A one-to-many relationship with the Asignacion model.
 *
 * @package App\Models
 * @property string $id_semestrea
 * @property string $nombre_semestre
 * @property \Illuminate\Support\Carbon $fecha_inicio
 * @property \Illuminate\Support\Carbon $fecha_fin
 * @method static \Illuminate\Database\Eloquent\Builder|Semestre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Semestre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Semestre query()
 * @method static \Illuminate\Database\Eloquent\Builder|Semestre whereIdSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Semestre whereNombreSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Semestre whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Semestre whereFechaFin($value)
 */
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
