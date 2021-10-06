<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenidad extends Model
{
    use HasFactory;

    protected $table = 'amenidades';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
        'estado'
    ];

    public function colorAmenidad()
    {
        return $this->belongsTo(ViewParametros::class,'color','codigo_det')
        ->where('view_parametros.codigo_enc','=','COLAMD');
    }

    public function estadoAmenidad()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
