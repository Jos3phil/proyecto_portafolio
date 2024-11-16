<?php

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
        return $this->belongsToMany(User::class, 'TUsuarioRoles', 'id_rol', 'id_usuario', 'id_rol', 'id_usuario');
    }
}
