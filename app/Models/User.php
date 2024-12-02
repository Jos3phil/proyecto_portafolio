<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{ 
    use HasApiTokens, HasFactory, Notifiable;
    
    

    protected $table = 'TUsuario';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;
    protected $fillable = [
        'id_usuario',
        'Nombre',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        //'password' => 'hashed',
    ];
    public static function generateUserRoleId($userId, $roleType)
    {
        $rolePrefix = '';
        switch ($roleType) {
            case 'DOCENTE':
                $rolePrefix = 'UD';
                break;
            case 'SUPERVISOR':
                $rolePrefix = 'US';
                break;
            case 'ADMINISTRADOR':
                $rolePrefix = 'UA';
                break;
        }

            // Obtener el número del usuario
        $userNumber = intval(substr($userId, 1));

        // Generar el id_usuario_rol basado en el prefijo del rol y el número del usuario
        return $rolePrefix . str_pad($userNumber, 3, '0', STR_PAD_LEFT);
    }
    public static function generateUserId()
    {
        /**
         * Generates a new user ID based on the maximum existing user ID in the 'TUsuario' table.
         *
         * This function retrieves the maximum user ID from the 'TUsuario' table, increments it by one,
         * and returns the new user ID in the format 'U' followed by a zero-padded 3-digit number.
         * If no user ID exists in the table, it returns the initial user ID 'U001'.
         *
         * @return string The newly generated user ID.
         */
        $lastId = DB::table('TUsuario')->max('id_usuario');
        if (!$lastId) {
            return 'U001';
        }
        $lastIdNumber = intval(substr($lastId, 1));
        $newIdNumber = $lastIdNumber + 1;
        return 'U' . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);
    }
    public function getNameAttribute()
    {
        return $this->Nombre;
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'TUsuarioRoles', 'id_usuario', 'id_rol')
                    ->withPivot('id_usuario_rol')
                    ->withTimestamps();
    }   
    public function hasRole($roleType)
    {
        return $this->roles()->where('tipo_rol', $roleType)->exists();
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('tipo_rol', $roles)->exists();
    }
    /**
     * Relación uno a muchos con Asignacion (como supervisor)
     */
    public function asignaciones()
    {
        return $this->hasOne(Asignacion::class, 'id_supervisor', 'id_usuario');
    }
    /**
     * Relación muchos a través con User (docentes asignados)
     */
    public function docentesAsignados()
    {
        return $this->hasManyThrough(User::class, Asignacion::class, 'id_supervisor', 'id_usuario', 'id_usuario', 'id_docente');
    }

}
