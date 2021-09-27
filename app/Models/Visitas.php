<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitas extends Model
{
    use HasFactory;

    protected $table = 'visitas';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_usuario_creo',
        'fecha_creo',
        'fecha_visita',
        'fecha_ingreso',
        'fecha_egreso',
        'nombre_visitante',
        'dpi_visitante',
        'placa_vehiculo',
        'estado'
    ];

}
