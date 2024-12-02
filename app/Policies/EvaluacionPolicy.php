<?php

namespace App\Policies;

use App\Models\Evaluacion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EvaluacionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['ADMINISTRADOR', 'SUPERVISOR','DOCENTE']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Evaluacion $evaluacion): bool
    {
        if ($user->hasRole('ADMINISTRADOR')) {
            return true;
        }

        if ($user->hasRole('SUPERVISOR')) {
            return $evaluacion->asignacion->id_supervisor === $user->id_usuario;
        }

        if ($user->hasRole('DOCENTE')) {
            return $evaluacion->asignacion->id_docente === $user->id_usuario;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Evaluacion $evaluacion): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Evaluacion $evaluacion): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Evaluacion $evaluacion): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Evaluacion $evaluacion): bool
    {
        //
    }
}
