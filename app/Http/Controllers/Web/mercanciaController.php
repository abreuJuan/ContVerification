<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Vanguard\mercancia;

class mercanciaController extends Controller
{

    private $mercancia;


    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
       
       $this->mercancia= new mercancia();

    }


    public function index()
    {
        $mercancias = $this->mercancia->paginated(
            20,
            Input::get('search')            
        );

       
      
        return view('mercancia.list', compact('mercancias'));
    }
    
    public function create()
    {
        return view('mercancia.add');
    }
    
    public function edit(mercancia $mercancia)
    {
        return view('mercancia.edit', compact('mercancia'));
    }
    
    public function update(mercancia $mercancia, \Vanguard\Http\Requests\Mercancia\UpdateMercanciaRequest $request)
    {       
      
        $data = $request->all();
        $data["modifiedDate"]=date("Y-m-d H:i:s");        
        $data["modifiedBy"]=Auth::id();
       // dd($confSms);
        $mercancia->update($data);        


        return redirect()->route('mercancia.index')
            ->withSuccess(trans('app.mercancia_updated_message'));
    }

    public function store(\Vanguard\Http\Requests\Mercancia\CreateMercanciaRequest $request)
    {
        $data=$request->all();
        $data["createdDate"]=date("Y-m-d H:i:s");
        $data["statusId"]=1;
        $data["createdBy"]=Auth::id();
        
        mercancia::create($data);
           
        return redirect()->route('mercancia.index')
            ->withSuccess(trans('app.mercancia_created_message'));      

    }

    public function delete(mercancia $mercancia)
    {        
        $mercancia->statusId=3;
        $mercancia->save();      

        return redirect()->route('mercancia.index')
            ->withSuccess(trans('app.mercancia_deleted_message'));
    }

}
