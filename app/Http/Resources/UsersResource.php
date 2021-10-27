<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'path_img' => $this->path_img,
            'estado' => $this->estado,
            'role_id' => $this->roles[0]->id,
            'role_name' => $this->roles[0]->name,
            'password' => $this->password,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoUsuario !== null ? $this->estadoUsuario->descripcion_det : '',
        ];
    }
}
