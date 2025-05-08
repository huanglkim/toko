<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuRoles extends Model
{
    use SoftDeletes;

    protected $table = 'menu_roles';
    protected $guarded = ['id'];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function menu()
    {
        return $this->belongsTo(Menus::class);
    }
}
