<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
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
            'descripcion' => $this->descripcion,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'informacion_general' => $this->informacion_general,
            'fecha_creacion' => $this->fecha_creacion,
            'img_perfil' => $this->img_perfil,
            'img_header' => $this->img_header,
            'pagina_web' => $this->pagina_web,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoProveedor !== null ? $this->estadoProveedor->descripcion_det : ''
        ];
    }
}
