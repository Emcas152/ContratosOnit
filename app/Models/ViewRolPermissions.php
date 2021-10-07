<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewRolPermissions extends Model
{
    use HasFactory;

    protected $table = 'life_db.view_rol_permissions';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'nivel_json',
        'url',
        'ext',
        'icon',
        'estado',
        'orden',
        'opcion',
        'role_id'
    ];
}
