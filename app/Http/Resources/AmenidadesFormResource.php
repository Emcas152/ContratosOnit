<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AmenidadesFormResource extends JsonResource
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
            'id' => $this->id !== null ? $this->id : 'null', 
            'label' => $this->nombre !== null ? $this->nombre : 'null', 
            'color' => $this->color !== null ? $this->color : 'null', 
            
        ];
    }
}
