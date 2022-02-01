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
            'name' => $this->usuario !== null ? $this->usuario->name : '',
            'telefono' => $this->usuario !== null ? $this->usuario->telefono : '',
            'email' => $this->usuario !== null ? $this->usuario->email : '',
            'descripcion' => $this->descripcion,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'informacion_general' => $this->informacion_general,
            'fecha_creacion' => $this->fecha_creacion,
            'path_perfil' => $this->path_perfil,
            'path_header' => $this->path_header,
            'pagina_web' => $this->pagina_web,
            'categoria' => $this->categoria,
            'categoria_descripcion' => $this->categoriaProveedor !== null ? $this->categoriaProveedor->descripcion_det : '',
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoProveedor !== null ? $this->estadoProveedor->descripcion_det : ''
        ];
    }
}
