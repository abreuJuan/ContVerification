<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Events\Settings\Updated as SettingsUpdated;
use Illuminate\Http\Request;
use Settings;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Vanguard\configuracionServicio;
use Vanguard\configuracionServicioEspacio;
use Vanguard\servicio;
use Auth;
use Vanguard\Support\Enum\ServicioEnum;

/**
 * Class SettingsController
 * @package Vanguard\Http\Controllers
 */
class SettingsController extends Controller
{
    private $servicioSetting;
    private $configuracionServicioEspacio;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->servicioSetting= new configuracionServicio();
        $this->configuracionServicioEspacio= new configuracionServicioEspacio();
    }

    /**
     * Display general settings page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function general()
    {
        return view('settings.general');
    }

    /**
     * Display Authentication & Registration settings page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auth()
    {
        return view('settings.auth');
    }

    /**
     * Handle application settings update.
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $this->updateSettings($request->except("_token"));

        return back()->withSuccess(trans('app.settings_updated'));
    }

    /**
     * Update settings and fire appropriate event.
     *
     * @param $input
     */
    private function updateSettings($input)
    {
        foreach ($input as $key => $value) {
            Settings::set($key, $value);
        }

        Settings::save();

        event(new SettingsUpdated);
    }

    /**
     * Enable system 2FA.
     *
     * @return mixed
     */
    public function enableTwoFactor()
    {
        $this->updateSettings(['2fa.enabled' => true]);

        return back()->withSuccess(trans('app.2fa_enabled'));
    }

    /**
     * Disable system 2FA.
     *
     * @return mixed
     */
    public function disableTwoFactor()
    {
        $this->updateSettings(['2fa.enabled' => false]);

        return back()->withSuccess(trans('app.2fa_disabled'));
    }

    /**
     * Enable registration captcha.
     *
     * @return mixed
     */
    public function enableCaptcha()
    {
        $this->updateSettings(['registration.captcha.enabled' => true]);

        return back()->withSuccess(trans('app.recaptcha_enabled'));
    }

    /**
     * Disable registration captcha.
     *
     * @return mixed
     */
    public function disableCaptcha()
    {
        $this->updateSettings(['registration.captcha.enabled' => false]);

        return back()->withSuccess(trans('app.recaptcha_disabled'));
    }

    /**
     * Display notification settings page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notifications()
    {
        return view('settings.notifications');
    }
    
    public function saveServicioSettings(Request $request)
    {
        
        $this->servicioSetting->where("id",">","0")->update(["active"=>0]);
        $checked=$request->get("checked");
        
        if(is_array($checked))
        {
            for($i=0;$i<sizeof($checked);$i++){
                $this->servicioSetting->where("id",$checked[$i])->update(["active"=>1,"modifiedDate"=>date("Y-m-d"),"modifiedBy"=>Auth::id()]);
            }
        }
        
        return back()->withSuccess(trans('app.updated_servicio_en_patio'));
    }
    
    public function servicioEnPatioActive()
    {
       $servicios= $this->servicioSetting->join("servicio AS s","s.id","=","configuracion_servicio.servicio_id")
               ->where("configuracion_servicio.statusId",1)
               ->selectRaw("configuracion_servicio.*,s.descripcion")->get();
      
       return view('settings.servicio',compact("servicios"));
    }
    
    public function servicioEspacio()
    {
       $servicios = servicio::where("statusId",1)->get();
       $espacioPorDias = [];      
       $allowScanner = 0;
       if( Input::get("servicio") )
       {
           $espacioPorDias = $this->configuracionServicioEspacio->getEspacioByServicioId(Input::get("servicio"))->get();  
           $allowScanner = servicio::getAllowScanner( Input::get("servicio") );
       }       
     
       return view('settings.servicio_espacio',compact("servicios","espacioPorDias","allowScanner"));
    }
    
    public function saveEspacioServicioSettings(\Vanguard\Http\Requests\settings\CreateServicioEspacioRequest $request)
    {
        $this->configuracionServicioEspacio->where("servicio_id",Input::get("servicio_id"))->update(["statusId"=>0]);
        //dd(Input::all());
        $interval = $request->get("interval") == 0 ? null : $request->get("interval") ;

        Input::merge(["interval" => $interval]);
        $data = Input::all()+["modifiedBy"=>Auth::id(),"modifiedDate"=>Date("Y-m-d"),"statusId"=>1];        
       
        $this->configuracionServicioEspacio->where("servicio_id",Input::get("servicio_id"))->create($data);
        
        $this->allowOrDisableScanner( Input::get("servicio_id"), $request );

        return back()->withSuccess(trans('app.updated_servicio_espacio'));
    }

    public function allowOrDisableScanner( $servicio_id, $request )
    {
        $allow_scanner = $request->get("allow_scanner") ? 1 : 0 ;

        $old_allow_scanner = servicio::getAllowScanner( $servicio_id );

        if( $old_allow_scanner == $allow_scanner ) return false;

        if( $allow_scanner == 1 )
        {
            servicio::enableAllowScanner( $servicio_id );
        }
        else
        {
            servicio::disableAllowScanner( $servicio_id );
        }

        return true;

    }
            
    
}
