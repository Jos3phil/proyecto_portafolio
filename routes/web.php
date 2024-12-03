<?php

/**
 * Web Routes
 *
 * Here is where you can register web routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * contains the "web" middleware group. Now create something great!
 *
 * Routes:
 * - GET /: Returns the welcome view.
 * - GET /home: Calls the index method of HomeController and names the route 'home'.
 * - GET /register: Calls the showRegistrationForm method of RegisterController and names the route 'register'.
 * - POST /register: Calls the register method of RegisterController.
 *
 * Middleware:
 * - Auth::routes(): Registers the authentication routes for the application.
 *
 * @file /C:/laragon/www/Proyecto_Portafolio/Proyecto_Portafolio/routes/web.php
 * @uses \Illuminate\Support\Facades\Route
 * @uses \Illuminate\Support\Facades\Auth
 * @uses \App\Http\Controllers\Auth\RegisterController
 */
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\CriterioEvaluacionController;
use App\Http\Controllers\SeccionEvaluacionController;

use App\Http\Controllers\SemestreController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
//permite que exista un capa de seguridad en la aplicacion para acceder a ciertas rutas
Route::middleware(['auth','role:ADMINISTRADOR'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');    
    Route::get('/users/{userId}/assign-role', [UserController::class, 'showAssignRoleForm'])->name('users.showAssignRoleForm');
    Route::post('/users/{userId}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');
});
Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {
    Route::get('/asignaciones/create', [AsignacionController::class, 'create'])->name('asignaciones.create');
    Route::post('/asignaciones/store', [AsignacionController::class, 'store'])->name('asignaciones.store');
    Route::get('/asignaciones', [AsignacionController::class, 'index'])->name('asignaciones.index');
    Route::get('/asignaciones/{id}/edit', [AsignacionController::class, 'edit'])->name('asignaciones.edit');
    Route::put('/asignaciones/{id}', [AsignacionController::class, 'update'])->name('asignaciones.update');
    Route::delete('/asignaciones/{id}', [AsignacionController::class, 'destroy'])->name('asignaciones.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/supervisor/docentes', [AsignacionController::class, 'tusDocentes'])->name('supervisor.docentes');
    Route::controller(EvaluacionController::class)->group(function () {
    Route::get('/evaluaciones', 'index')->name('evaluaciones.index');
    Route::get('/evaluaciones/create', 'create')->name('evaluaciones.create'); // Usando /create en lugar de /crear
    Route::post('/evaluaciones', 'store')->name('evaluaciones.store');
    Route::get('/evaluaciones/{idEvaluacion}', 'show')->name('evaluaciones.show');
    Route::get('/evaluaciones/{idEvaluacion}/detail', 'detail')->name('evaluaciones.detail');
    Route::delete('/evaluaciones/{idEvaluacion}', 'destroy')->name('evaluaciones.destroy');
    Route::get('/evaluaciones/{idEvaluacion}/continuar', 'continueEvaluation')->name('evaluaciones.continue');
    Route::post('/evaluaciones/continuar', 'storeContinuation')->name('evaluaciones.storeContinuation');
    });
});

Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {
    Route::get('/criterios/create', [CriterioEvaluacionController::class, 'create'])->name('criterios.create');
    Route::post('/criterios', [CriterioEvaluacionController::class, 'store'])->name('criterios.store');
    Route::get('/criterios', [CriterioEvaluacionController::class, 'index'])->name('criterios.index');
    Route::get('/criterios/{id}/edit', [CriterioEvaluacionController::class, 'edit'])->name('criterios.edit');
    Route::put('/criterios/{id}', [CriterioEvaluacionController::class, 'update'])->name('criterios.update');
    Route::delete('/criterios/{id}', [CriterioEvaluacionController::class, 'destroy'])->name('criterios.destroy');
});

Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {
    Route::get('/secciones/create', [SeccionEvaluacionController::class, 'create'])->name('secciones.create');
    Route::post('/secciones', [SeccionEvaluacionController::class, 'store'])->name('secciones.store');
    Route::get('/secciones', [SeccionEvaluacionController::class, 'index'])->name('secciones.index');
});


Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {
    Route::get('/semestres/create', [SemestreController::class, 'create'])->name('semestres.create');
    Route::post('/semestres', [SemestreController::class, 'store'])->name('semestres.store');
    Route::get('/semestres', [SemestreController::class, 'index'])->name('semestres.index');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/roles/switch', [RoleController::class, 'switchRole'])->name('roles.switch');
    Route::post('/roles/switch', [RoleController::class, 'setActiveRole'])->name('roles.setActive');
});