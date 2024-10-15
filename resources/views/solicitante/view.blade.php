
@extends('layouts.app')

@section('page-title', trans('app.solicitud_details_main_title'))
@section('page-heading', trans('app.solicitud_details_main_sub_title'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
       @lang('app.solicitud_details_main_title')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">     
       


        <p><b>@lang('app.solicitud_details_title')</b></p>
        
        @include('solicitante.partials.details',["solicitante" => $individuo])
        
        <p>&nbsp;</p>
        <button class='btn btn-primary' onclick="window.history.back()">
            @lang('app.return')
        </button>

    </div>
</div>



@stop

