<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Apartamento;

class Visitantes extends Model
{
    use HasFactory;

    protected $table = 'visitantes';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'id_usuario_creo',
        'id_inquilino',
        'dpi_visita',
        'nombre_visita',
        'estado'
    ];

    public function usuarioCreo()
    {
        return $this->belongsTo(User::class,'id_usuario_creo');
    }

    public function usuarioInquilino()
    {
        return $this->belongsTo(User::class,'id_inquilino');
    }

    public function inquilinoApartamento()
    {
        return $this->belongsTo(Apartamento::class,'id_inquilino', 'id_inquilino');
    }

    public function estadoVisitante()
    {
        return $this->belongsTo(ViewParametros::class,'estado','codigo_det')
        ->where('view_parametros.codigo_enc','=','STSGEN');
    }
}
