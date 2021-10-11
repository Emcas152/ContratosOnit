<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_usuario_creo',
        'nombre',
        'descripcion',
        'tipo_noticia',
        'prioridad',
        'fecha_publicacion',
        'estado'
    ];

    public function usuarioNoticia()
    {
        return $this->belongsTo(User::class,'id_usuario_creo');
    }

    public function tipoNoticia()
    {
        return $this->belongsTo(ViewParametros::class,'tipo_noticia','codigo_det')
        ->where('view_parametros.codigo_enc','=','TINOTI');
    }

    public function prioridadNoticia()
    {
        return $this->belongsTo(ViewParametros::class,'prioridad','codigo_det')
        ->where('view_parametros.codigo_enc','=','PRNOTI');
    }

    public function estadoNoticia()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
