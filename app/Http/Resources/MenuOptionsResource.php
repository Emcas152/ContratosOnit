<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MenuSubOptionsResource;
use App\Models\ViewMenu;

class MenuOptionsResource extends JsonResource
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
            'state' => $this->url,
            'name' => $this->name,
            'type' => $this->ext,
            'icon' => $this->icon,
            'children' => MenuSubOptionsResource::collection(ViewMenu::where([['opcion','=',$this->id],['role_id','=',$this->role_id]])->get()),
        ];
    }
}
