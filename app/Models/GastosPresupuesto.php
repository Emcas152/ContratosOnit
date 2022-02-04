<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastosPresupuesto extends Model
{
    use HasFactory;

    protected $table = 'gastos_presupuesto';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_presupuesto',
        'descripcion',
        'numero_factura',
        'serie_factura',
        'total',
        'path_imagen',
        'estado'
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class,'id_presupuesto');
    }

    public function estadoGastoPresupuesto()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
