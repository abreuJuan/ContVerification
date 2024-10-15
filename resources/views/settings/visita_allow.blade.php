@extends('layouts.app')

@section('page-title', trans('app.visita_allow_title'))
@section('page-heading', trans('app.visita_allow_subtitle'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">
        @lang('app.settings')
    </li>
    <li class="breadcrumb-item active">
        @lang('app.visita_allow_content_title')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="panel-heading"></div>
            <div class="card-body">
                <div class="col-md-6">
                <h5 class="card-title">
                    @lang('app.visita_allow_content_title')
                </h5>

                {!! Form::open(['route' => 'visita.allow.store', 'id' => 'visita-allow-form']) !!}

                    
                        <div class='form-group'>
                            <label for="allowVisita">@lang('app.visita_allow_amaunt_field')</label>
                            <input type="number" name="cantidad" class="form-control" min="0" max="99" maxlength="2" value='{{$cantidadAllow}}'
                                   oninput="maxLengthCheck(this)" placeholder="@lang('app.visita_allow_amaunt_field')"/>
                        </div>
                   
                            

                  
                    <button type="submit" class="btn btn-primary mt-3">
                        @lang('app.update_settings')
                    </button>
                    
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
{!! JsValidator::formRequest('Vanguard\Http\Requests\visitaPermitida\CreateVisitaPermitidaRequest', '#visita-allow-form') !!}
<script>        

        function maxLengthCheck(object)
        {
           
          if(object.value=="")
            object.value = object.value.substring(0,object.value.length-1);        
          
          if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
        }  
        
     
</script>
    

@stop