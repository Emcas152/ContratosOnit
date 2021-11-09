<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Condominio;
use App\Models\ViewParametros;
use App\Models\Edificio;

class Amenidad extends Model
{
    use HasFactory;

    protected $table = 'amenidades';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_condominio',
        'nombre',
        'descripcion',
        'color',
        'image',
        'estado'
    ];

    public function condominio()
    {
        return $this->belongsTo(Condominio::class,'id_condominio');
    }

    public function edificios(){
        return $this->belongsToMany(Edificio::class);
    }

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
