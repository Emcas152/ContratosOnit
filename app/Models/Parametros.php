<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ParametrosDetalle;

class Parametros extends Model
{
    use HasFactory;

    protected $table = 'parametros';

    protected $primaryKey = 'id';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];

    public function parametros_det()
    {
        return $this->hasMany(ParametrosDetalle::class,'id_parametro','id');
    }
}
