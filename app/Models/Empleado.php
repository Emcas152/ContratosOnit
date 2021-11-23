<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'puesto',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'id_usuario');
    }

    public function puestoEmpleado()
    {
        return $this->belongsTo(ViewParametros::class,'puesto','codigo_det')
        ->where('view_parametros.codigo_enc','=','PTSTRA');
    }

    public function estadoEmpleado()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSEMP');
    }
}
