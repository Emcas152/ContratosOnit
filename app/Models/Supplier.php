<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'descripcion',
        'direccion',
        'informacion_general',
        'fecha_creacion',
        'path_perfil',
        'path_header',
        'pagina_web',
        'categoria',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'id_usuario');
    }

    public function categoriaProveedor()
    {
        return $this->belongsTo(ViewParametros::class,'categoria','codigo_det')
        ->where('view_parametros.codigo_enc','=','CATPRO');
    }

    public function estadoProveedor()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
