<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

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

        $lastUserRole = DB::table('TUsuarioRoles')
            ->where('id_usuario', $userId)
            ->where('id_rol', $roleType)
            ->orderBy('id_usuario_rol', 'desc')
            ->first();

        if (!$lastUserRole) {
            return $rolePrefix . '001';
        }

        $lastIdNumber = intval(substr($lastUserRole->id_usuario_rol, 2));
        $newIdNumber = $lastIdNumber + 1;
        return $rolePrefix . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);
    }
    public static function generateUserId()
    {
        $lastUser = DB::table('TUsuario')->orderBy('id_usuario', 'desc')->first();
        if (!$lastUser) {
            return 'U001';
        }
        $lastIdNumber = intval(substr($lastUser->id_usuario, 1));
        $newIdNumber = $lastIdNumber + 1;
        return 'U' . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);
    }
    public function getNameAttribute()
    {
        return $this->Nombre;
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'TUsuarioRoles', 'id_usuario', 'id_rol', 'id_usuario', 'id_rol');
    }   
    public function hasRole($roleType)
    {
        return $this->roles()->where('tipo_rol', $roleType)->exists();
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('tipo_rol', $roles)->exists();
    }
}
