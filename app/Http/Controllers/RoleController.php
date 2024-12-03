<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Mostrar la interfaz para cambiar el rol activo.
     *
     * @return \Illuminate\View\View
     */
    public function switchRole()
    {
        $user = Auth::user();
        $roles = $user->roles()->pluck('tipo_rol')->toArray();

        return view('roles.switch', compact('roles'));
    }

    /**
     * Cambiar el rol activo del usuario.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setActiveRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        $user = Auth::user();
        $role = $request->input('role');

        if ($user->hasRole($role)) {
            $user->setActiveRole($role);
            return redirect()->back()->with('success', 'Rol activo cambiado a ' . $role);
        }

        return redirect()->back()->with('error', 'No tienes el rol seleccionado.');
    }
}
