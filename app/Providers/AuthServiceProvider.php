<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Evaluacion;
use App\Policies\EvaluacionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Evaluacion::class => EvaluacionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Definir permiso para acceder al panel de administrador
        Gate::define('admin-access', function ($user) {
            return $user->hasRole('ADMINISTRADOR');
        });

        // Definir permiso para supervisores
        Gate::define('supervisor-access', function ($user) {
            return $user->hasRole('SUPERVISOR');
        });   

        Gate::define('docente-access', function ($user) {
            return $user->hasRole('DOCENTE');
        });
            // Gate Compuesto para Evaluaciones
        Gate::define('evaluaciones-access', function ( $user) {
            return $user->hasAnyRole(['SUPERVISOR', 'ADMINISTRADOR']);
        });
    }
}
