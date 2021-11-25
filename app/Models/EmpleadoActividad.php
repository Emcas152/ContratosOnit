<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoActividad extends Model
{
    use HasFactory;

    protected $table = 'actividades_empleado';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_empleado',
        'nombre',
        'descripcion',
        'hora_inicio',
        'hora_fin'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class,'id_empleado');
    }
}
