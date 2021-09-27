<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApartamentosResource extends JsonResource
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
            'numero_apartamento' => $this->numero_apartamento,
            'torre_apartamento' => $this->torre_apartamento,
            'nivel_apartamento' => $this->nivel_apartamento,
            'medida_interior' => $this->medida_interior,
            'precio_m_interior' => $this->precio_m_interior,
            'medida_exterior' => $this->medida_exterior,
            'precio_m_exterior' => $this->precio_m_exterior,
            'precio_venta' => $this->precio_venta,
            'minimo_enganche' => $this->minimo_enganche,
            'minimo_reserva' => $this->minimo_reserva,
            'estado' => $this->estado,
            'proyecto' =>$this->proyectos !== null ? $this->proyectos->descripcion : 'null'
        ];
    }
}
