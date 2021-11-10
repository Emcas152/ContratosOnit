<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarSocialResource extends JsonResource
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
            'color' => $this->Amenidades !== null ? $this->Amenidades->color : '', 
            'title' => $this->titulo,
            'id' => $this->id,
            'start' => $this->fecha_reserva,
            'end' => $this->fecha_finaliza_reserva,
            'usuario' => $this->id_usuario,
            'usuario_nombre' => $this->usuarioCalendario !== null ? $this->usuarioCalendario->name : '',
            'apartamento' => $this->inquilino !== null ? $this->inquilino->nombre : '',
            'calendar' => $this->id_area,
            'description' => $this->descripcion,
            'comment' => $this->comentarios,
            'invitados' => collect($this->invitados)->map->only('id', 'nombre_visita'),
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoCalendario !== null ? $this->estadoCalendario->descripcion_det : ''
        ];
    }
}
