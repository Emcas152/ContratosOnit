<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosDetalle extends Model
{
    use HasFactory;

    protected $table = 'parametros_detalle';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_parametro',
        'codigo',
        'descripcion'
    ];
}
