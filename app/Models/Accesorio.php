<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Condominio;
use App\Models\ViewParametros;

class Accesorio extends Model
{
    use HasFactory;

    protected $table = 'accesorios';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_condominio',
        'nombre',
        'descripcion',
        'categoria',
        'estado'
    ];

    public function condominio()
    {
        return $this->belongsTo(Condominio::class,'id_condominio');
    }

    public function categoriaAccesorio()
    {
        return $this->belongsTo(ViewParametros::class,'categoria','codigo_det')
        ->where('view_parametros.codigo_enc','=','CATACC');
    }

    public function estadoAccesorio()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
