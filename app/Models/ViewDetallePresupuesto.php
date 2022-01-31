<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewDetallePresupuesto extends Model
{
    use HasFactory;
    protected $table = 'view_detalle_prosupuesto';

    public $incrementing = false;
}
