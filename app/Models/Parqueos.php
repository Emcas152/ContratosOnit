<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proyectos;

class Parqueos extends Model
{
    use HasFactory;

    protected $table = 'parqueos';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_proyecto',
        'numero_parqueo',
        'nivel_sotano',
        'tipo_parqueo',
        'medida_parqueo',
        'precio_venta',
        'estado'
    ];

    public function proyectos()
    {
        return $this->belongsTo(Proyectos::class,'id_proyecto');
    }
}
