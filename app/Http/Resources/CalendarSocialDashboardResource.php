<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarSocialDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'backgroundColor' => $this->Amenidades !== null ? $this->Amenidades->color : '', 
            'color' => $this->Amenidades !== null ? $this->Amenidades->color : 'null', 
            'title' => $this->titulo,
            'id' => $this->id,
            'start' => $this->fecha_reserva,
            'end' => $this->fecha_finaliza_reserva,
            'usuario' => $this->id_usuario,
            'usuario_nombre' => $this->usuarioCalendario !== null ? $this->usuarioCalendario->name : '',
            'apartamento' => $this->inquilino !== null ? $this->inquilino->nombre : '',
            'calendar' => $this->id_area,
            'amenidad' => $this->Amenidades !== null ? $this->Amenidades->nombre : '',
        ];
    }
}
