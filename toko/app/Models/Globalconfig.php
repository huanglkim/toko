<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Globalconfig extends Model
{
    use SoftDeletes;

    protected $table = 'globalconfig';
    protected $guarded = ['id'];
}
