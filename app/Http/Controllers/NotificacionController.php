<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notificacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NotificacionesResource;

class NotificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::findOrfail($request->id);
        $notificacionesNoLeidas = $user->unreadNotifications;
        $totalNotificacionesNoLeidas = count($notificacionesNoLeidas);

        if (!count($notificacionesNoLeidas)) {
            return response(['data' => [],'code'=>204]);  
        }
        return response(['data'=> NotificacionesResource::collection($notificacionesNoLeidas), 'total' => $totalNotificacionesNoLeidas ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notificacion  $notificacion
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead(Request $request)
    {
        $user = User::findOrfail($request->id);
        $notificacionesLeidas = $user->unreadNotifications->markAsRead();

        return response(['data'=> []]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notificacion  $notificacion
     * @return \Illuminate\Http\Response
     */
    public function markNotification(Request $request)
    {
        $user = User::findOrfail($request->id_usuario);
        $notificacionesLeida = $user->unreadNotifications->when($request->id, function($query) use ($request){
            return $query->where('id', $request->id);
        })->markAsRead();

        return response(['data'=> []]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notificacion  $notificacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notificacion $notificacion)
    {
        //
    }
}
