<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Vanguard\Support\Enum\ServicioEnum;
use Carbon\Carbon;

class session extends Model
{
   
    protected $table = 'sessions';
    public $timestamps = false;
    
    
    protected $fillable = [         
         "id","user_id","ip_address","user_agent","payload","last_activity"
    ];
  
    public static function getActiveSessionByUserAgent( $user_agent )
    {
        return session::where("user_agent",$user_agent)->where("user_id",">",0)->get()->first();
    }

}
