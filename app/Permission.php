<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['name', 'display_name', 'description'];

    protected $casts = [
        'removable' => 'boolean'
    ];
    
    public function existPermision($permiso)
    {
        return $this->where("name",$permiso)->count()>0?true:false;
    }
}
