<?php

namespace App\Http\Resources;
use App\Models\User;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'fullName' => $this->name,
            'username' => $this->name,
            'email' => $this->email,
            /* 'role' => 'admin', */
            'ability' => [
                [
                  'action'=> "manage",
                  'subject'=> "all",
                ]
                ],
            'role' => $this->roles !== null ? $this->roles[0]['name'] : '',
            'role_id' => $this->roles !== null ? $this->roles[0]['id'] : '',
        ];
    }
}
