<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MenuOptionsButtonResource;
use App\Models\ViewMenu;

class MenuSubOptionsResource extends JsonResource
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
            'icon' => $this->icon,
            'type' => $this->ext,
            'permission' => MenuOptionsButtonResource::collection(ViewMenu::where([['sub_btn','=',$this->id],['role_id','=',$this->role_id]])->get()),
        ];
    }
}
