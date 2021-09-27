<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
       /*  return
        [
            'id' => $this->id,
            'id_usuario_creo' => $this->id_usuario_creo,
            'fecha_creo' => $this->fecha_creo,
            'fecha_visita' => $this->fecha_visita,
            'fecha_ingreso' => $this->fecha_ingreso,
            'fecha_egreso' => $this->fecha_egreso,
            'nombre_visitante' => $this->nombre_visitante,
            'dpi_visitante' => $this->dpi_visitante,
            'placa_vehiculo' => $this->placa_vehiculo,
            'estado' =>  $this->estado,

        ]; */
    }

    
    

}
