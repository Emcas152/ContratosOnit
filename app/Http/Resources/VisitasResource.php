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
        return
        [
            'id' => $this->id,
            'id_usuario_creo' => $this->id_usuario_creo,
            'nombre_creo' => $this->usuarioVisita !== null ? $this->usuarioVisita->name : '',
            'apartamento' => $this->inquilino !== null ? $this->inquilino->nombre : '',
            'fecha_creo' => $this->fecha_creo,
            'fecha_visita' => $this->fecha_visita,
            'fecha_ingreso' => $this->fecha_ingreso,
            'fecha_egreso' => $this->fecha_egreso,
            'id_visitante' => $this->id_visitante,
            'nombre_visitante' =>$this->visitantes !== null ? $this->visitantes->nombre_visita : 'null',
            'dpi_visitante' =>$this->visitantes !== null ? $this->visitantes->dpi_visita : 'null',
            'placa_vehiculo' => $this->placa_vehiculo,
            'estado' =>  $this->estado,
            'estado_descripcion' => $this->estadoVisita !== null ? $this->estadoVisita->descripcion_det : ''
        ]; 
    }

    
    

}
