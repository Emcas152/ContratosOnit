<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParqueosResource extends JsonResource
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
            'id_proyecto' => $this->id_proyecto,
            'proyecto' => $this->proyectos->descripcion,
            'numero_parqueo' => $this->numero_parqueo,
            'nivel_sotano' => $this->nivel_sotano,
            'tipo_parqueo' => $this->tipo_parqueo,
            'medida_parqueo' => $this->medida_parqueo,
            'precio_venta' => $this->precio_venta,
            'estado' => $this->estado
        ];
    }
}
