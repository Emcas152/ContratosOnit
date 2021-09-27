<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
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
            //'borderColor' => $this->Amenidades !== null ? $this->Amenidades->color : 'null',
            'backgroundColor' => $this->Amenidades !== null ? $this->Amenidades->color : 'null', 
            'color' => $this->Amenidades !== null ? $this->Amenidades->color : 'null', 
            'title' => $this->titulo,
            'id' => $this->id,
            'start' => $this->fecha_reserva,
            'end' => $this->fecha_finaliza_reserva,
            'usuario' => $this->id_usuario,
            'extendedProps' => [
                'calendar' => $this->id_area,
                'description' => $this->descripcion,
                'comment' => $this->comentarios,
                'estado' => $this->estado,
            ]
        ];
    }
}
