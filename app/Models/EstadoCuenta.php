<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCuenta extends Model
{
    use HasFactory;

    protected $table = 'estado_cuenta';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_condominio',
        'id_apartamento',
        'id_usuario_aprobo',
        'nombre_pago',
        'path_comprobante',
        'tipo',
        'fecha_generado',
        'fecha_subida',
        'fecha_aprobado',
        'descripcion_rechazo',
        'monto',
        'estado'
    ];

    public function condominio()
    {
        return $this->belongsTo(Condominio::class,'id_condominio');
    }

    public function apartamentos()
    {
        return $this->belongsTo(Apartamento::class,'id_apartamento');
    }

    public function usuarioAprobo()
    {
        return $this->belongsTo(User::class,'id_usuario_aprobo');
    }

    public function tipoEstadoCuenta()
    {
        return $this->belongsTo(ViewParametros::class,'tipo','codigo_det')
        ->where('view_parametros.codigo_enc','=','TPAGO');
    }

    public function estadoEstadoCuenta()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSEC');
    }
}
