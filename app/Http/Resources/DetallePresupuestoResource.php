<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetallePresupuestoResource extends JsonResource
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
            'id' => $this->id,
            'id_encabezado' => $this->id_encabezado,
            'subcategoria' => $this->subcategoria,
            'subcategoria_descripcion' => $this->subcategoriaPresupuesto !== null ? $this->subcategoriaPresupuesto->descripcion_det : '',
            'subtotal' => $this->subtotal,
        ];
    }
}
