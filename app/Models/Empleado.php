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
        'rol',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'id_usuario');
    }

    public function horariosEmpleado(){
        return $this->hasMany(EmpleadoHorario::class, 'id_empleado');
    }

    public function actividadesEmpleado(){
        return $this->hasMany(EmpleadoActividad::class, 'id_empleado');
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
