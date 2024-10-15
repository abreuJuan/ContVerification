@extends('layouts.app')

@section('page-title', trans('app.dia_feriado_title'))
@section('page-heading', trans('app.dia_feriado_subtitle'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
       @lang('app.dia_feriado_content_title')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <h4>
            @lang('app.add_dia_feriado_title')
        </h4>
        
       <br/> 
       @include('settings.partials.add_dia_feriado')
        
        
       <p>&nbsp;</p>
       <p>&nbsp;</p>
       
        <h4>
            @lang('app.dia_feriado_list_title')
        </h4>
       
        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Input::get('search') }}"
                               placeholder="buscar">

                            <span class="input-group-append">
                                @if (Input::has('search') && Input::get('search') != '')
                                    <a href="{{ route('conf_dia_feriado.index') }}"
                                           class="btn btn-light d-flex align-items-center text-muted"
                                           role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>


            </div>
        </form>

        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                <tr>
                    
                    <th class="min-width-80">@lang('app.dia_feriado_desciption')</th>
                    <th class="min-width-80">@lang('app.dia_feriado_title')</th>
                    <th class="min-width-150">@lang('app.dia_feriado_created_date')</th>                
                    <th class="text-center min-width-150">@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                    @if (count($confDiaFeriados))
                        @foreach ($confDiaFeriados as $confDiaFeriado)
                             
                        <tr>         

                            <td>{{$confDiaFeriado->descripcion}}</td>
                            <td>{{$confDiaFeriado->hollydate}}</td>
                            <td>{{$confDiaFeriado->createdDate}}</td>                            
                            <td class="text-center align-middle">        

                            <a href="{{ route('conf_dia_feriado.delete', $confDiaFeriado->id) }}"
                               class="btn btn-icon"
                               
                               data-toggle="tooltip"
                               data-placement="top"
                               data-method="DELETE"
                               data-confirm-title="@lang('app.please_confirm')"
                               data-confirm-text="@lang('app.are_you_sure_delete_dia_feriado_blocked')"
                               data-confirm-delete="@lang('app.yes_delete_him')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                        </tr>
                        
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3"><em>@lang('app.no_records_found')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $confDiaFeriados->render() !!}

@stop