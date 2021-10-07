<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitantesResource extends JsonResource
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
            /*  'borderColor' => $this->Amenidades !== null ? $this->Amenidades->color : 'null',
             'backgroundColor' => $this->Amenidades !== null ? $this->Amenidades->color : 'null', */
             'avatar' => '@/assets/images/avatars/1-small.png',
             'name' => $this->nombre_completo,
             'id' => $this->id,
         ];
    }
}
