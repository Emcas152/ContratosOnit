<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratosInternet extends Model
{
    use HasFactory;

    protected $table = 'view_contratos_internet';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'celular',
        'edad',
        'email',
        'estado',
        'estado_civil',
        'fecha_traslado',
        'identificacion',
        'nacionalidad',
        'nit',
        'numero_apartamento',
        'tipo_apartamento',
        'tipo_documento',
        'sexo',
        'tipo_servicio',
        'tabla0',
        'tabla1',
        'tabla2',
        'tabla3',
        'file_name',
        'tipo_documento',
        'file_name_contrato',
        'file_name_energia',
        'file_name_agua',
        'fecha_registro',
        'tipo_proyecto',
        'tipo_plan',
        'torre',
        'path_copia_dpi',
        'path_representacion',
        'nombre_representacion',
        'no_registro_mercantil',
        'folio',
        'libro',
        'empresa',
        'fecha_nacimiento',
        'tabla4',
        'nombre_factura',
        'nit_factura',
        'servicio_agua',
        'status',
        'correlativo_internet',
    ];
}
