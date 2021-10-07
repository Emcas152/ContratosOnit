<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'id_visitante',
        'placa_vehiculo',
        'estado'
    ];

    public function usuarioVisita()
    {
        return $this->belongsTo(User::class,'id_usuario_creo');
    }

    public function visitantes()
    {
        return $this->belongsTo(Visitantes::class,'id_visitante');
    }

}
