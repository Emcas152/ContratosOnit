<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_condominio',
        'nombre',
        'descripcion',
        'path',
        'image',
        'estado'
    ];

    public function condominio()
    {
        return $this->belongsTo(Condominio::class,'id_condominio');
    }

    public function estadoDocumento()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
