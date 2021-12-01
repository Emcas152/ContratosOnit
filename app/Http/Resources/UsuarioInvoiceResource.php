<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioInvoiceResource extends JsonResource
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
            'path_img' => $this->usuario !== null ? $this->usuario->path_img : '',
            'rol' => $this->usuario->roles !== null ? $this->usuario->roles[0]->name : '',
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'nit' => $this->nit,
            'dpi' => $this->dpi,
            'apartamento' => $this->usuario->apartamentos !== null ? $this->usuario->apartamentos->nombre : '',
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoUsuarioInvoice !== null ? $this->estadoUsuarioInvoice->descripcion_det : ''
        ];
    }
}
