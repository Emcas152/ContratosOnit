<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    use HasFactory;

    protected $table = 'presupuesto_enc';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'fecha_inicio',
        'fecha_vencimiento',
        'id_condominio',
        'descripcion',
        'presupuesto',
        'fecha_edicion',
        'usuario_creo',
        'estado'
    ];

    public function detallePresupuesto()
    {
        return $this->hasMany(DetallePresupuesto::class,'id_encabezado');
    }

    public function condominio()
    {
        return $this->belongsTo(Condominio::class,'id_condominio');
    }

    public function usuarioCreo()
    {
        return $this->belongsTo(User::class,'usuario_creo');
    }

    public function estadoPresupuesto()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
