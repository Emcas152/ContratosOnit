<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudAccesorioResource extends JsonResource
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
            'id_accesorio' => $this->id_accesorio,
            'accesorio_descripcion' =>$this->accessorios !== null ? $this->accessorios->nombre : 'null',
            'id_usuario_solicito' => $this->id_usuario_solicito,
            'usuario_solicito_descripcion' => $this->usuarioSolicito !== null ? $this->usuarioSolicito->name : '',
            'usuario_apartamento' => $this->usuarioApartamento !== null ? $this->usuarioApartamento->nombre : '',
            'id_usuario_creo' => $this->id_usuario_creo,
            'usuario_creo_descripcion' => $this->usuarioCreo !== null ? $this->usuarioCreo->name : '',
            'id_autorizo' => $this->id_autorizo,
            'usuario_autorizo_descripcion' => $this->usuarioAutorizo !== null ? $this->usuarioAutorizo->name : '',
            'fecha_solicitud' => $this->fecha_solicitud,
            'fecha_prestamo' => $this->fecha_prestamo,
            'fecha_devolucion' => $this->fecha_devolucion,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoSolicitud !== null ? $this->estadoSolicitud->descripcion_det : ''
        ];
    }
}
