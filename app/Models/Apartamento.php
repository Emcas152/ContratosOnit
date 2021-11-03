<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Edificio;
use App\Models\User;

class Apartamento extends Model
{
    use HasFactory;

    protected $table = 'apartamentos';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_inquilino',
        'id_edificio',
        'nombre',
        'nivel',
        'telefono',
        'estado'
    ];

    public function edificios()
    {
        return $this->belongsTo(Edificio::class,'id_edificio');
    }

    public function inquilinos()
    {
        return $this->belongsTo(User::class,'id_inquilino');
    }

    public function estadoApartamento()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSAPT');
    }
}
