<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePresupuesto extends Model
{
    use HasFactory;

    protected $table = 'presupuesto_det';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_encabezado',
        'subcategoria',
        'subtotal',
        'categoria',
        'descripcion'
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class,'id_encabezado');
    }

    public function subcategoriaPresupuesto()
    {
        return $this->belongsTo(ViewCategoriaSubcategorias::class,'subcategoria','codigo_det');
    }

    public function categoriaPresupuesto()
    {
        return $this->belongsTo(ViewParametros::class,'categoria','codigo_enc');
    }
}
