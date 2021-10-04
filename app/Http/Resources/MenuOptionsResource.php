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
        $result = [
            'route' => $this->url,
            'title' => $this->name,
            'icon' => $this->icon,
            'type' => $this->ext,
        ];

        $children = MenuSubOptionsResource::collection(ViewMenu::where([['opcion','=',$this->id],['role_id','=',$this->role_id]])->get());

        if(count($children)){
            $result['children'] = $children;
        }

        return $result;
        
    }
}
