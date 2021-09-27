<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartamentos extends Model
{
    use HasFactory;

    protected $table = 'apartamentos';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_proyecto',
        'numero_apartamento',
        'torre_apartamento',
        'nivel_apartamento',
        'medida_interior',
        'precio_m_interior',
        'medida_exterior',
        'precio_m_exterior',
        'precio_venta',
        'minimo_enganche',
        'minimo_reserva',
        'estado'
    ];


    public function proyectos()
    {
        return $this->belongsTo(Proyectos::class,'id_proyecto');
    }
}
