@extends('layouts.app')

@section('page-title', trans('app.servicio_confirmar_patio_settings'))
@section('page-heading', trans('app.servicio_confirmar_patio_settings'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">
        @lang('app.settings')
    </li>
    <li class="breadcrumb-item active">
        @lang('app.servicio_patio')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="panel-heading"></div>
            <div class="card-body">
                <h5 class="card-title">
                    @lang('app.servicio_patio')
                </h5>

                {!! Form::open(['route' => 'servicio_patio.updated', 'id' => 'servicio-settings']) !!}
                  @foreach($servicios AS $x)
                 
                    <div class="form-group my-4">
                        <div class="d-flex align-items-center">
                            
                            <label class="switch">
                              <input type="checkbox"
                                     name="checked[]"         
                                     value="{{$x->id}}"                                       
                                     {{ $x->active ? 'checked' : '' }}>
                              <span class="slider round"></span>
                            </label>                    
                            
                            
                            <div class="ml-3 d-flex flex-column">
                                <label class="mb-0">{{$x->descripcion}}</label>
                                <small class="pt-0 text-muted">
                                    

                                </small>
                            </div>
                        </div>
                    </div>
                  

                  @endforeach
                  
                  

                  
                    <button type="submit" class="btn btn-primary mt-3">
                        @lang('app.update_settings')
                    </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@stop