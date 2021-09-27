<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuAccionesStringResource extends JsonResource
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
            'id' => (string)$this->id,
            'url' => $this->url,
            'name' => $this->name,
            'ext' => $this->ext,
            'icon' => $this->icon,
            'estado' => $this->estado,
            'orden' => $this->orden,
            'opcion' => $this->opcion,
            
        ];
    }
}
