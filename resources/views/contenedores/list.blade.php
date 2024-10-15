@extends('layouts.app')

@section('page-title', trans('app.contenedores_title'))
@section('page-heading', trans('app.contenedores_subtitle'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('solicitud.index') }}">@lang('app.solicitud_title')</a>
    </li>

    <li class="breadcrumb-item active">
       @lang('app.contenedores_content_title')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">       
       


        <p><b>@lang('app.contenedor_list_amount') {{$contenedores->total()}}</b></p>
        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                <tr>                    
                    <th>@lang('app.contenedor_list_name')</th>
                    <th>@lang('app.contenedor_list_estatus')</th> 
                    <th>@lang('app.contenedor_list_tipo')</th> 
                  {{--  <th class="text-center min-width-150">@lang('app.action')</th> --}}
                </tr>
                </thead>
                <tbody>
                    @if (count($contenedores))
                        @foreach ($contenedores as $contenedor)
                             
                          @include('contenedores.partials.row')
                        
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

{!! $contenedores->render() !!}

@stop