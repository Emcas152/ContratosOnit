<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'guard_name',
        'created_at',
        'updated_at',
        'nivel_json',
        'opcion',
        'sub_btn',
        'url',
        'ext',
        'icon',
        'orden',
        'estado'
    ];
}
