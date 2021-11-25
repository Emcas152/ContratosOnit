<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\MenuAccionesController;
use App\Http\Controllers\ParametrosDetalleController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;

use App\Http\Controllers\VisitasController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\VisitantesController;

use App\Http\Controllers\CondominioController;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\ApartamentoController;
use App\Http\Controllers\AccesorioController;
use App\Http\Controllers\AmenidadController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\SolicitudAccesorioController;

use App\Http\Controllers\ViewInquilinoController;
use App\Http\Controllers\CalendarSocialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;

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

Route::middleware(['auth:api'])->group(function () 
{
    Route::post('/menu', [MenuAccionesController::class, 'index'])->name('menu.index');
    Route::post('/submenu/permissions', [MenuAccionesController::class, 'MenuSubPermissions'])->name('menu.index');

    Route::get('/showMenu', [MenuAccionesController::class, 'showMenu'])->name('menu.showMenu');
    Route::put('/showMenu/edit/{id}', [MenuAccionesController::class, 'update'])->name('menu.update');
    Route::get('/showMenuAction/{id}', [MenuAccionesController::class, 'showMenuAction'])->name('menu.Action');

    Route::get('/parametros/{codigo}', [ParametrosDetalleController::class, 'show'])->name('menu.show');

    Route::post('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::post('/roles/select', [RolesController::class, 'show'])->name('roles.show');
    Route::post('/roles/create', [RolesController::class, 'store'])->name('roles.store');
    Route::put('/roles/edit/{id}', [RolesController::class, 'update'])->name('roles.update');
    Route::delete('/roles/delete/{id}', [RolesController::class, 'destroy'])->name('roles.destroy');

    Route::get('/roles/menu/{id}', [RolesController::class, 'rolesMenu'])->name('roles.rolesMenu');
    Route::get('/roles/menu_btn/{role}/{id}', [RolesController::class, 'roleMenuButton'])->name('roles.roleMenuButton');
    Route::get('/roles/menu_btn/all/{role}/{id}', [RolesController::class, 'roleMenuButtonAll'])->name('roles.roleMenuButtonAll');
    Route::get('/roles/show/{id}', [RolesController::class, 'show'])->name('roles.show');
    Route::get('/roles/asignacion/{role}/{menu}/{estado}', [RolesController::class, 'rolesAssign'])->name('roles.rolesAssign'); 
    
    Route::post('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users/select', [UsersController::class, 'show'])->name('users.show');
    Route::post('/users/create', [UsersController::class, 'store'])->name('users.store');
    Route::put('/users/edit/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

    Route::post('/condominium', [CondominioController::class, 'index'])->name('condominium.index');
    Route::post('/condominium/select', [CondominioController::class, 'show'])->name('condominium.show');
    Route::post('/condominium/create', [CondominioController::class, 'store'])->name('condominium.store');
    Route::put('/condominium/edit/{id}', [CondominioController::class, 'update'])->name('condominium.update');
    Route::delete('/condominium/delete/{id}', [CondominioController::class, 'destroy'])->name('condominium.destroy');

    Route::post('/building', [EdificioController::class, 'index'])->name('building.index');
    Route::post('/building/select', [EdificioController::class, 'show'])->name('building.show');
    Route::post('/building/create', [EdificioController::class, 'store'])->name('building.store');
    Route::put('/building/edit/{id}', [EdificioController::class, 'update'])->name('building.update');
    Route::delete('/building/delete/{id}', [EdificioController::class, 'destroy'])->name('building.destroy');

    Route::post('/apartment', [ApartamentoController::class, 'index'])->name('apartment.index');
    Route::post('/apartment/select', [ApartamentoController::class, 'show'])->name('apartment.show');
    Route::post('/apartment/create', [ApartamentoController::class, 'store'])->name('apartment.store');
    Route::put('/apartment/edit/{id}', [ApartamentoController::class, 'update'])->name('apartment.update');
    Route::delete('/apartment/delete/{id}', [ApartamentoController::class, 'destroy'])->name('apartment.destroy');

    Route::post('/accessories', [AccesorioController::class, 'index'])->name('accessories.index');
    Route::post('/accessories/select', [AccesorioController::class, 'show'])->name('accessories.show');
    Route::post('/accessories/create', [AccesorioController::class, 'store'])->name('accessories.store');
    Route::put('/accessories/edit/{id}', [AccesorioController::class, 'update'])->name('accessories.update');
    Route::delete('/accessories/delete/{id}', [AccesorioController::class, 'destroy'])->name('accessories.destroy');

    Route::post('/amenities', [AmenidadController::class, 'index'])->name('amenities.index');
    Route::post('/amenities/select', [AmenidadController::class, 'show'])->name('amenities.show');
    Route::post('/amenities/create', [AmenidadController::class, 'store'])->name('amenities.store');
    Route::post('/amenities/edit/{id}', [AmenidadController::class, 'update'])->name('amenities.update');
    Route::delete('/amenities/delete/{id}', [AmenidadController::class, 'destroy'])->name('amenities.destroy');

    Route::post('/news', [NoticiaController::class, 'index'])->name('news.index');
    Route::post('/news/{user}', [NoticiaController::class, 'show'])->name('news.show');
    Route::post('/news/create/add', [NoticiaController::class, 'store'])->name('news.store');
    Route::put('/news/edit/{id}', [NoticiaController::class, 'update'])->name('news.update');
    Route::delete('/news/delete/{id}', [NoticiaController::class, 'destroy'])->name('news.destroy');

    Route::post('/accessory-request', [SolicitudAccesorioController::class, 'index'])->name('accessory-request.index');
    Route::post('/accessory-request/create', [SolicitudAccesorioController::class, 'store'])->name('accessory-request.store');
    Route::put('/accessory-request/edit/{id}', [SolicitudAccesorioController::class, 'update'])->name('accessory-request.update');
    Route::put('/accessory-request/{id}/{action}', [SolicitudAccesorioController::class, 'change_state'])->name('accessory-request.change_state');

    Route::post('/guests', [VisitantesController::class, 'index'])->name('guests.index');
    Route::post('/guests/select', [VisitantesController::class, 'show'])->name('guests.show');
    Route::post('/guests/create', [VisitantesController::class, 'store'])->name('guests.store');
    Route::put('/guests/edit/{id}', [VisitantesController::class, 'update'])->name('guests.update');
    Route::delete('/guests/delete/{id}', [VisitantesController::class, 'destroy'])->name('guests.destroy');

    Route::post('/visits', [VisitasController::class, 'index'])->name('visits.index');
    Route::post('/visits/create', [VisitasController::class, 'store'])->name('visits.store');
    Route::put('/visits/edit/{id}', [VisitasController::class, 'update'])->name('visits.update');
    Route::put('/visits/{id}/{action}', [VisitasController::class, 'change_state'])->name('visits.change_state');

    Route::post('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar/create', [CalendarController::class, 'store'])->name('calendar.store');
    Route::put('/calendar/edit/{id}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::put('/calendar/{id}/{action}', [CalendarController::class, 'change_state'])->name('calendar.change_state');
    Route::delete('/calendar/delete/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');

    Route::post('/calendar-social', [CalendarSocialController::class, 'index'])->name('calendar-social.index');
    Route::post('/calendar-social/create', [CalendarSocialController::class, 'store'])->name('calendar-social.store');
    Route::put('/calendar-social/edit/{id}', [CalendarSocialController::class, 'update'])->name('calendar-social.update');
    Route::put('/calendar-social/{id}/{action}', [CalendarSocialController::class, 'change_state'])->name('calendar-social.change_state');
    Route::delete('/calendar-social/delete/{id}', [CalendarSocialController::class, 'destroy'])->name('calendar-social.destroy');

    Route::post('/tenants', [ViewInquilinoController::class, 'show'])->name('tenants.show');

    Route::post('/dashboard/visits-input', [DashboardController::class, 'visitsInput'])->name('dashboard.visitsInput');
    Route::post('/dashboard/visits-active', [DashboardController::class, 'visitsActive'])->name('dashboard.visitsActive');
    Route::post('/dashboard/amenities-authorize', [DashboardController::class, 'amenitiesAuthorize'])->name('dashboard.amenitiesAuthorize');
    Route::post('/dashboard/amenities-available', [DashboardController::class, 'amenitiesAvailable'])->name('dashboard.amenitiesAvailable');
    Route::post('/dashboard/amenities-maintenance', [DashboardController::class, 'amenitiesMaintenance'])->name('dashboard.amenitiesMaintenance');
    Route::post('/dashboard/accessory-request', [DashboardController::class, 'accessoryRequest'])->name('dashboard.accessoryRequest');
    Route::post('/dashboard/visits-week', [DashboardController::class, 'visitsWeek'])->name('dashboard.visitsWeek');
    Route::post('/dashboard/amenities-authorize-maintenance', [DashboardController::class, 'amenitiesAuthorizeMaintenance'])->name('dashboard.amenitiesAuthorizeMaintenance');

    Route::post('/employees', [EmpleadoController::class, 'index'])->name('employees.index');
    Route::post('/employees/list', [EmpleadoController::class, 'show'])->name('employees.show');
    Route::post('/employees/create', [EmpleadoController::class, 'store'])->name('employees.store');
    Route::post('/employees/{id}', [EmpleadoController::class, 'showById'])->name('employees.showById');
    Route::post('/employees/edit/{id}', [EmpleadoController::class, 'update'])->name('employees.update');
    Route::delete('/employees/delete/{id}', [EmpleadoController::class, 'destroy'])->name('employees.destroy');
    
});