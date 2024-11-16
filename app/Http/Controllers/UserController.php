<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function showAssignRoleForm($userId)
    {
        $user = User::findOrFail($userId);
        return view('users.assign_role', compact('user'));
    }
    public function assignRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $role = Role::where('tipo_rol', $request->input('role'))->first();

        if ($role) {
            $idUsuarioRol = User::generateUserRoleId($userId, $role->tipo_rol);
            $user->roles()->attach($role->id_rol, ['id_usuario_rol' => $idUsuarioRol]);
            return response()->json(['message' => 'Rol asignado satisfactoriamente.']);
        } else {
            return response()->json(['message' => 'Rol no encontrado.'], 404);
        }
    }
}
