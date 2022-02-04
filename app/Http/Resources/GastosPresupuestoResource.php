<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GastosPresupuestoResource extends JsonResource
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
            'id_presupuesto' => $this->id_presupuesto,
            'presupuesto_descripcion' => $this->presupuesto !== null ? $this->presupuesto->descripcion : '',
            'descripcion' => $this->descripcion,
            'numero_factura' => $this->numero_factura,
            'serie_factura' => $this->serie_factura,
            'path_imagen' => $this->path_imagen,
            'total' => $this->total,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoGastoPresupuesto !== null ? $this->estadoGastoPresupuesto->descripcion_det : '',
        ];
    }
}
