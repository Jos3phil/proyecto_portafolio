<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;

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
Route::middleware(['auth'])->group(function () {
    Route::get('/users/{userId}/assign-role', [UserController::class, 'showAssignRoleForm'])->name('users.showAssignRoleForm');
    Route::post('/users/{userId}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');
});
