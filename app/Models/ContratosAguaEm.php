<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratosAguaEm extends Model
{
    use HasFactory;

    protected $table = 'view_agua_empresas';

    protected $primaryKey = 'id';
    
    public $timestamps = true;

    protected $fillable = [
        'id_empresa',
        'tipo_proyecto',
        'numero_apartamento',
        'torre',
        'tipo_apartamento',
        'email_facturacion',
        'file_name',
        'file_name_contrato',
        'file_name_agua',
        'file_name_internet',
        'fecha_traslado',
        'tipo_servicio',
        'tabla0',
        'tabla1',
        'tabla2',
        'tabla3',
        'tabla4',
        'servicio_agua',
        'estado',
        'correlativo_agua'
    ];
}
