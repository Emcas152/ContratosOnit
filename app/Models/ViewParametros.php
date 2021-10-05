<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewParametros extends Model
{
    use HasFactory;

    protected $table = 'life_db.view_parametros';

    public $incrementing = false;

    protected $fillable = [
        'id_enc',
        'codigo_enc',
        'descripcion_enc',
        'id_det',
        'id_parametro_det',
        'codigo_det',
        'descripcion_det'
    ];
}
