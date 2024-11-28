<?php
/**
 * Class Role
 *
 * This class represents the Role model which interacts with the 'TRol' table.
 * It includes methods for generating unique role IDs and defining relationships with other models.
 *
 * @package App\Models
 * @property string $id_rol The primary key for the role.
 * @property string $tipo_rol The type of the role.
 * @property string $descripcion The description of the role.
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role generateRoleId()
 * 
 * @mixin \Eloquent
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'TRol';
    protected $primaryKey = 'id_rol';
    public $incrementing = false;
    protected $fillable = ['id_rol', 'tipo_rol', 'descripcion'];

    // Método para generar un id_rol único
    public static function generateRoleId()
    {
        $lastRole = self::orderBy('id_rol', 'desc')->first();
        if (!$lastRole) {
            return 'R001';
        }
        $lastIdNumber = intval(substr($lastRole->id_rol, 1));
        $newIdNumber = $lastIdNumber + 1;
        return 'R' . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);
    }

    // Relación con el modelo User
    public function users()
    {
        return $this->belongsToMany(User::class, 'TUsuarioRoles', 'id_rol', 'id_usuario')
                    ->withPivot('id_usuario_rol')
                    ->withTimestamps();
    }
}
