<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends Model
{
    use SoftDeletes;

    protected $table = 'role';
    protected $guarded = [
        'id'
    ];
    public function User()
    {
        return $this->hasMany(Users::class)->Active();
    }
}
