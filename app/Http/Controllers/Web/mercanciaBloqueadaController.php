<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Vanguard\mercanciaBloqueada;
use Vanguard\mercancia;

class mercanciaBloqueadaController extends Controller
{

    private $mercancia;


    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
        
       $this->mercancia= new mercanciaBloqueada();
    }


    public function index()
    {
        $mercancias = $this->mercancia->getMercanciasPaginated(
            20,
            Input::get('search')            
        );

        $mercancias_list=$this->mercancia->getActiveMercancia();
       
      
        return view('settings.mercancia_bloqueada', compact('mercancias','mercancias_list'));
    }
    
    
    public function store(Request $request)
    {
        $data=$request->all();
        
        $data["createdDate"]=date("Y-m-d H:i:s");
        $data["createdBy"]=Auth::id();
        $data["statusId"]=1;
        
        for($i=0;$i<sizeof($data["descripcion"]);$i++)
        {
            $data["mercancia_id"]=$data["descripcion"][$i];
            mercanciaBloqueada::create($data);
        }
        
        return redirect()->route('mercancia.bloqueada')
            ->withSuccess(trans('app.mercancia_blocked_created_message'));
        
    }
    
    public function delete(mercanciaBloqueada $mercancia)
    {        
        $mercancia->statusId=3;
        $mercancia->save();      

        return redirect()->route('mercancia.bloqueada')
            ->withSuccess(trans('app.mercancia_blocked_deleted_message'));
    }
   
}
