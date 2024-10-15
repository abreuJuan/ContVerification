@extends('layouts.app')

@section('page-title', trans('app.servicio_espacio_settings'))
@section('page-heading', trans('app.servicio_espacio_settings'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">
        @lang('app.servicio_espacio')
    </li>
    <li class="breadcrumb-item active">
        @lang('app.servicio_espacio')
    </li>
@stop

@section('content')

@include('partials.messages')
<style>
    .label_hora{
        display:none;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="panel-heading"></div>
            <div class="card-body">
                <h5 class="card-title">
                    @lang('app.servicio_espacio')
                </h5>

              {!! Form::open(['route' => 'servicio_espacio.index', 'id' => 'servicio-form','method'=>'get']) !!}
                <div class="col-md-7 mt-2 mt-md-0">
                   <div class="form-group">
                      <label for="Servio">@lang('app.servicio_espacio_select')</label>
                    <select class="form-control" name="servicio" id="servicio">
                        <option value="">--NONE--</option>
                    @foreach($servicios AS $x)
                      <option value="{{$x->id}}" @if(!empty(Input::get("servicio")) && Input::get("servicio")==$x->id) selected @endif>
                              {{$x->descripcion}}
                      </option>
                    @endforeach
                  </select>
                   </div>
                </div>
                
               {!! Form::close() !!}             
                  
              
              
               @if(Input::get("servicio"))              
               
               {!! Form::open(['route' => 'servicio_espacio.updated', 'id' => 'servicio-dia-form']) !!}
               <p>
                   <div class="col-md-12 mt-2 mt-md-0">
                     <label for="Servio">@lang('app.servicio_espacio_periodo')</label>
                   </div>
               </p>
               
               <div class="col-md-6">
                   
                     <div class="form-group">
                         <label for="Servio">@lang('app.servicio_espacio_interval_field')</label>
                         <input type='number' name='interval' id='interval' class="form-control" min="0" max="12" maxlength = "2" oninput="maxLengthCheck(this)" 
                                placeholder="@lang('app.servicio_espacio_interval_field')"
                                value="{{isset($espacioPorDias[0]["interval"])?$espacioPorDias[0]["interval"]:''}}" onkeyup="showLabelDay(this)" onchange="showLabelDay(this)"/>
                    </div>
                   
               </div>
               
               <div class="col-md-6">
                   
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="allow_scanner" name="allow_scanner" @if( $allowScanner == 1 ) checked @endif >
                        <label class="form-check-label">@lang('app.servicio_allow_scanner')</label>
                      </div>
               </div>
              
               </div>
               
               
               
               <p>
                   <div class="col-md-12 mt-2 mt-md-0">
                     <label for="Servio">@lang('app.servicio_espacio_dias')</label>
                   </div>
               </p>
                

                @include('settings.partials.servicio_espacio_daily_form',[ "espacioPorDias" => isset($espacioPorDias)?$espacioPorDias:""])

                  
                  <input type="hidden" value="{{input::get("servicio")}}" name="servicio_id"/>
                  
                    <button type="submit" class="btn btn-primary mt-3">
                        @lang('app.update_settings')
                    </button>
                  {!! Form::close() !!}  
              @endif
                
            </div>
        </div>
    </div>
</div> 
@stop


@section('scripts')
   {!! JsValidator::formRequest('Vanguard\Http\Requests\settings\CreateServicioEspacioRequest', '#servicio-dia-form') !!}
   
    <script>
        
        $("#servicio").change(function () {            
            $("#servicio-form").submit();
        });

        function maxLengthCheck(object)
        {
          if(object.value=="")
            object.value = object.value.substring(0,object.value.length-1);        
          
          if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
        }


      $(document).ready(function(){
          showLabelDay(document.getElementById("interval"));
      });
       function showLabelDay(object)
       {
           if(object.value!=0 && !isNaN(object.value))
           {
               showLabelHora();
           }
           else
           {
               hideLabelHora();
           }
               
       }
       
       function hideLabelHora()
       {
           var labels=document.getElementsByClassName("label_hora");
           for(var i=0;i<labels.length;i++)
               labels[i].style.display="none";
       }
       
       function showLabelHora()
       {
           var labels=document.getElementsByClassName("label_hora");
           for(var i=0;i<labels.length;i++)
               labels[i].style.display="block";
       }
       
        
     </script>
 @stop