<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Edificio;
use App\Models\ViewParametros;

class Condominio extends Model
{
    use HasFactory;

    protected $table = 'condominios';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'parqueo_visitantes',
        'estado'
    ];

    public function edificios()
    {
        return $this->hasMany(Edificio::class,'id_condominio');
    }

    public function estadoCondominio()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
