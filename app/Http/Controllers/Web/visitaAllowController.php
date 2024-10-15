<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Vanguard\visitaAllow;
use Auth;

class visitaAllowController extends Controller
{      

    private $visitaAllow;

    public function __construct()
    {  
       $this->middleware('auth');
       $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
        
      $this->visitaAllow=new visitaAllow();
    }
    
    public function index()
    {
        $visitaAmount=$this->visitaAllow->getActive()->get();
        $cantidadAllow=isset($visitaAmount[0]["cantidad"])?$visitaAmount[0]["cantidad"]:0;
        
        return view('settings.visita_allow',compact("visitaAmount",'cantidadAllow'));
    }

    public function store(Request $request)
    {
        
        $this->visitaAllow->where("statusId",1)->update(["statusId"=>3]);
        
        $data=$request->all();
        $data["createdDate"]=date("Y-m-d H:i:s");
        $data["createdBy"]=Auth::id();
        $data["statusId"]=1;
        
        $this->visitaAllow->create($data);
        
        return redirect()->route('visita.permitida')
            ->withSuccess(trans('app.visita_allow_created_successfully'));              
        
    }
  
}
