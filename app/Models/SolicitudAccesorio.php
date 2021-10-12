<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accesorio;
use App\Models\User;
use App\Models\Apartamento;

class SolicitudAccesorio extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_accesorio',
        'id_usuario_solicito',
        'id_usuario_creo',
        'id_autorizo',
        'fecha_solicitud',
        'fecha_prestamo',
        'estado'
    ];

    public function accessorios()
    {
        return $this->belongsTo(Accesorio::class,'id_accesorio');
    }

    public function usuarioSolicito()
    {
        return $this->belongsTo(User::class,'id_usuario_solicito');
    }

    public function usuarioApartamento()
    {
        return $this->belongsTo(Apartamento::class,'id_usuario_solicito', 'id_inquilino');
    }

    public function usuarioCreo()
    {
        return $this->belongsTo(User::class,'id_usuario_creo');
    }

    public function usuarioAutorizo()
    {
        return $this->belongsTo(User::class,'id_autorizo');
    }

    public function estadoSolicitud()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSSOL');
    }
}
