<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Amenidad;
use App\Models\User;
use App\Models\Apartamento;

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

    public function usuarioCalendario()
    {
        return $this->belongsTo(User::class,'id_usuario');
    }

    public function inquilino()
    {
        return $this->belongsTo(Apartamento::class,'id_usuario', 'id_inquilino');
    }

    public function Amenidades()
    {
        return $this->belongsTo(Amenidad::class,'id_area');
    }

    public function estadoCalendario()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSCAL');
    }
}
