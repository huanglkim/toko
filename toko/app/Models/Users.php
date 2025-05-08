<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $dates = ['deleted_at'];

    public function Role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    // public function Gajitemplate()
    // {
    //     return $this->belongsTo(Gajitemplate::class);
    // }
    public function LogOpr()
    {
        return $this->hasMany(LogOpr::class);
    }
    // public function skpj()
    // {
    //     return $this->hasOne(Skpj::class)->where('status', 1);
    // }
}
