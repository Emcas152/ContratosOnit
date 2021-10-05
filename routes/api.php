<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\MenuAccionesController;
use App\Http\Controllers\ParametrosDetalleController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\ApartamentosController;
use App\Http\Controllers\ParqueosController;
use App\Http\Controllers\BodegasController;
use App\Http\Controllers\AmenidadesController;

use App\Http\Controllers\VisitasController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\VisitantesController;

use App\Http\Controllers\CondominioController;
use App\Http\Controllers\EdificioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::post('/visits/create', [VisitasController::class, 'store'])->name('visits.store');
Route::get('/visits', [VisitasController::class, 'index'])->name('visits.index');
Route::get('/visits/edit/{id}', [VisitasController::class, 'edit'])->name('visits.edit');
Route::put('/visits/{id}/{state}', [VisitasController::class, 'change_state'])->name('visits.change_state');
Route::patch('/visits/{id}', [VisitasController::class, 'update'])->name('visits.update');

Route::post('/calendar/create', [CalendarController::class, 'store'])->name('calendar.store');
Route::post('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::put('/calendar/edit/{id}', [CalendarController::class, 'update'])->name('calendar.update');
Route::delete('/calendar/delete/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
 
Route::get('/visitantes', [VisitantesController::class, 'index'])->name('visitantes.index');


Route::get('/amenidades', [AmenidadesController::class, 'index'])->name('amenidades.index');
Route::get('/amenidades/name', [AmenidadesController::class, 'getName'])->name('amenidades.getName');


Route::middleware(['auth:api'])->group(function () 
{
    Route::post('/menu', [MenuAccionesController::class, 'index'])->name('menu.index');
    Route::post('/submenu/permissions', [MenuAccionesController::class, 'MenuSubPermissions'])->name('menu.index');

    Route::get('/showMenu', [MenuAccionesController::class, 'showMenu'])->name('menu.showMenu');
    Route::put('/showMenu/edit/{id}', [MenuAccionesController::class, 'update'])->name('menu.update');
    Route::get('/showMenuAction/{id}', [MenuAccionesController::class, 'showMenuAction'])->name('menu.Action');

    Route::get('/parametros/{id}', [ParametrosDetalleController::class, 'index'])->name('menu.index');

    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::post('/roles/create', [RolesController::class, 'store'])->name('roles.store');
    Route::put('/roles/edit/{id}', [RolesController::class, 'update'])->name('roles.update');
    Route::delete('/roles/delete/{id}', [RolesController::class, 'destroy'])->name('roles.destroy');

    Route::get('/roles/menu/{id}', [RolesController::class, 'rolesMenu'])->name('roles.rolesMenu');
    Route::get('/roles/menu_btn/{role}/{id}', [RolesController::class, 'roleMenuButton'])->name('roles.roleMenuButton');
    Route::get('/roles/menu_btn/all/{role}/{id}', [RolesController::class, 'roleMenuButtonAll'])->name('roles.roleMenuButtonAll');
    Route::get('/roles/show/{id}', [RolesController::class, 'show'])->name('roles.show');
    Route::get('/roles/asignacion/{role}/{menu}/{estado}', [RolesController::class, 'rolesAssign'])->name('roles.rolesAssign'); 
    
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users/create', [UsersController::class, 'store'])->name('users.store');
    Route::put('/users/edit/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id_user}', [UsersController::class, 'destroy'])->name('users.destroy');

    Route::get('/project', [ProyectosController::class, 'index'])->name('project.index');
    Route::get('/project/act', [ProyectosController::class, 'projectAct'])->name('project.act');
    Route::post('/project/create', [ProyectosController::class, 'store'])->name('project.store');
    Route::put('/project/edit/{id}', [ProyectosController::class, 'update'])->name('project.update');

    Route::post('/condominium', [CondominioController::class, 'index'])->name('condominium.index');
    Route::post('/condominium/create', [CondominioController::class, 'store'])->name('condominium.store');
    Route::put('/condominium/edit/{id}', [CondominioController::class, 'update'])->name('condominium.update');
    Route::delete('/condominium/delete/{id}', [CondominioController::class, 'destroy'])->name('condominium.destroy');

    Route::post('/building', [EdificioController::class, 'index'])->name('building.index');
    Route::post('/building/create', [EdificioController::class, 'store'])->name('building.store');
    Route::put('/building/edit/{id}', [EdificioController::class, 'update'])->name('building.update');
    Route::delete('/building/delete/{id}', [EdificioController::class, 'destroy'])->name('building.destroy');

});