<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EstadoCuentaResource extends JsonResource
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
            'id_condominio' => $this->id_condominio,
            'condominio_descripcion' => $this->condominio !== null ? $this->condominio->nombre : '',
            'id_apartamento' => $this->id_apartamento,
            'apartamento_descripcion' => $this->apartamentos !== null ? $this->apartamentos->nombre : '',
            'nombre_pago' => $this->nombre_pago,
            'path_comprobante' => $this->path_comprobante,
            'tipo' => $this->tipo,
            'tipo_descripcion' => $this->tipoEstadoCuenta !== null ? $this->tipoEstadoCuenta->descripcion_det : '',
            'fecha_generado' => $this->fecha_generado,
            'fecha_subida' => $this->fecha_subida,
            'fecha_aprobado' => $this->fecha_aprobado,
            'descripcion_rechazo' => $this->descripcion_rechazo,
            'usuario_aprobo' => $this->usuarioAprobo !== null ? $this->usuarioAprobo->name : '',
            'monto' => $this->monto,
            'estado' => $this->estado,
            'estado_descripcion' => $this->estadoEstadoCuenta !== null ? $this->estadoEstadoCuenta->descripcion_det : ''
        ];
    }
}
