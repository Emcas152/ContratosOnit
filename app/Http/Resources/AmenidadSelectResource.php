<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AmenidadSelectResource extends JsonResource
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
            'id' => $this->id !== null ? $this->id : 'null', 
            'label' => $this->nombre !== null ? $this->nombre : 'null', 
            'color' => $this->color !== null ? $this->color : 'null', 
        ];
    }
}
