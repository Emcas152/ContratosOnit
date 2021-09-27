<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewMenu extends Model
{
    use HasFactory;

    protected $table = 'aslan_db.view_menu';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'nivel_json',
        'opcion',
        'sub_btn',
        'url',
        'ext',
        'icon',
        'orden',
        'permission_id',
        'role_id'
    ];


}
