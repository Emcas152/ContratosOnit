<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contratos extends Model
{
    use HasFactory;

    protected $table = 'contratos';

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
        'tabla1',
        'tabla2',
        'file_name',
        'tipo_documento',
        'file_name_contrato',
        'fecha_registro',
        'tipo_proyecto',
        'tipo_plan',
        'torre'
    ];
}
