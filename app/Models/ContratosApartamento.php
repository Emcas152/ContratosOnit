<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratosApartamento extends Model
{
    use HasFactory;

    protected $table = 'contratos_apartamentos';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_empresa',
        'tipo_proyecto',
        'numero_apartamento',
        'torre',
        'tipo_apartamento',
        'file_name',
        'file_name_contrato',
        'fecha_traslado',
        'estado'
    ];
}
