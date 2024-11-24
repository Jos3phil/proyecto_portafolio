<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function showAssignRoleForm($userId)
    {
        $user = User::findOrFail($userId);
        return view('users.assign_role', compact('user'));
    }
    public function assignRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $role = Role::where('tipo_rol', $request->input('role'))->first();
        $currentUser = Auth::user();


        // Verificar si el usuario autenticado es administrador o el mismo usuario
        if ($user->hasRole('ADMINISTRADOR') || $currentUser->id_usuario === $userId) {
            if ($role) {
                $idUsuarioRol = User::generateUserRoleId($userId, $role->tipo_rol);
                $user->roles()->attach($role->id_rol, ['id_usuario_rol' => $idUsuarioRol]);
                return redirect()->route('users.showAssignRoleForm', $userId)->with('mensaje', 'Role assigned successfully.');
            } else {
                return redirect()->route('users.showAssignRoleForm', $userId)->withErrors(['Role not found.']);
            }
        } else {
            return redirect()->route('users.showAssignRoleForm', $userId)->withErrors(['You do not have permission to assign roles.']);
        }
    }
}
