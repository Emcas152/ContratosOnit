<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadosProcesos extends Model
{
    use HasFactory;
    protected $table = 'estados_procesos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'proceso',
        'sts_inicial',
        'sts_final',
        'tabla',
        'descripcion'
    ];
}
