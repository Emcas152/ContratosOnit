<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
        [
            'id' => $this->id,
            'id_usuario' => $this->id_usuario,
            'usuario_descripcion' => $this->usuario !== null ? $this->usuario->name : '',
            'telefono' => $this->usuario !== null ? $this->usuario->telefono : '',
            'email' => $this->usuario !== null ? $this->usuario->email : '',
            'path_img' => $this->usuario !== null ? $this->usuario->path_img : '',
            'rol' => $this->rol,
            'puesto' => $this->puesto,
            'puesto_descripcion' => $this->puestoEmpleado !== null ? $this->puestoEmpleado->descripcion_det : '',
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoEmpleado !== null ? $this->estadoEmpleado->descripcion_det : ''
        ];
    }
}
