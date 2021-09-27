<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Parqueos;
use App\Models\Apartamentos;
use App\Models\Bodegas;

class Proyectos extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'proyectos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'codigo',
        'descripcion',
        'ubicacion',
        'cantidad_apartamentos',
        'niveles',
        'amenidades',
        'estado'
    ];

    public function parqueos()
    {
        return $this->hasMany(Parqueos::class,'id_proyecto');
    }

    public function apartamentos()
    {
        return $this->hasMany(Apartamentos::class,'id_proyecto');
    }

    public function bodegas()
    {
        return $this->hasMany(Bodegas::class,'id_proyecto');
    }
}
