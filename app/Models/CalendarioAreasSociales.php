<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Amenidades;

class CalendarioAreasSociales extends Model
{
    use HasFactory;

    protected $table = 'calendario_areas_sociales';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_area',
        'titulo',
        'descripcion',
        'comentarios',
        'id_usuario',
        'fecha_reserva',
        'hora_inicio',
        'hora_finaliza',
        'estado'
    ];

    public function Amenidades()
    {
        return $this->belongsTo(Amenidades::class,'id_area');
    }
}
