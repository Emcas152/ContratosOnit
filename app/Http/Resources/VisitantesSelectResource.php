<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitantesSelectResource extends JsonResource
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
             'avatar' => '@/assets/images/avatars/1-small.png',
             'nombre_visita' => $this->nombre_visita,
             'id' => $this->id,
         ];
    }
}
