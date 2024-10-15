@extends('layouts.app')

@section('page-title', trans('app.visitante_title'))
@section('page-heading', trans('app.visitante_subtitle'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('solicitud.index') }}">@lang('app.solicitud_title')</a>
    </li>

    <li class="breadcrumb-item active">
       @lang('app.visitante_content_title')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">       
       


        <p><b>@lang('app.visitante_list_amount') {{$visitantes->total()}}</b></p>
        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                <tr>                    
                    <th>@lang('app.visitante_list_name')</th>
                    <th>@lang('app.tipo_documento')</th>
                    <th>@lang('app.visitante_list_document')</th>  
                    <th>@lang('app.visitante_list_contenedor')</th>  
                    <th>@lang('app.visitante_list_rol')</th>
                    <th>@lang('app.visitante_list_estatus')</th> 
                    <th class="text-center min-width-150">@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                    @if (count($visitantes))
                        @foreach ($visitantes as $visitante)
                             
                          @include('visitante.partials.row')
                        
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{!! $visitantes->render() !!}

@if( count($visitantes) && $solicitud->entrada == 0 )
<div class= "row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('visitante.entrada',$hash) }}" class="btn btn-primary btn-rounded float-left">
                    <i class="fas fa-arrow-right"></i>
                    @lang('app.visitante_dar_entrada')
                </a>
            </div>
        </div>
    </div>	

@endif

@if( $solicitud->entrada == 1)
<div class="row">
   <div class="col-md-12">
         <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            @lang('app.visitante_entrada_ok')
         </div> 
   </div>
</div>

@endif

@stop