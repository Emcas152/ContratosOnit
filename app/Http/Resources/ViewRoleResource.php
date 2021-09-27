<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ViewRoleResource extends JsonResource
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
                'estado' => $this->estado,
                'asignado' => $this->asignado !== null ? $this->asignado : '0',
            ];
    }
}
