<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewInquilino extends Model
{
    use HasFactory;

    protected $table = 'view_inquilinos';

    public $incrementing = false;
}
