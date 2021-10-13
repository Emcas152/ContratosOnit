<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Apartamento;

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

    public function inquilino()
    {
        return $this->belongsTo(Apartamento::class,'id_usuario_creo', 'id_inquilino');
    }

    public function visitantes()
    {
        return $this->belongsTo(Visitantes::class,'id_visitante');
    }

    public function estadoVisita()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSVTA');
    }

}
