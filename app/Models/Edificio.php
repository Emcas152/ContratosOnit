<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Apartamento;
use App\Models\Condominio;
use App\Models\ViewParametros;

class Edificio extends Model
{
    use HasFactory;

    protected $table = 'edificios';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_condominio',
        'nombre',
        'descripcion',
        'niveles',
        'estado'
    ];

    public function apartamentos()
    {
        return $this->hasMany(Apartamento::class,'id_edificio');
    }

    public function condominios()
    {
        return $this->belongsTo(Condominio::class,'id_condominio');
    }

    public function estadoEdificios()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
