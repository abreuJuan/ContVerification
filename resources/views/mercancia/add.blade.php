@extends('layouts.app')

@section('page-title', trans('app.mercancia_title_create'))
@section('page-heading', trans('app.mercancia_subtitle_create'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('mercancia.index') }}">@lang('app.mercancia_return_list')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('app.mercancia_content_title_create')
    </li>
@stop

@section('content')

@include('partials.messages')

{!! Form::open(['route' => 'mercancia.store',  'id' => 'mercancia-form']) !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('app.mercancia_details')
                    </h5>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    @include('mercancia.partials.details', ['edit' => false])
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
               @lang('app.mercancia_submit')
            </button>
        </div>
    </div>
{!! Form::close() !!}

<br>
@stop

@section('scripts')
    {!! JsValidator::formRequest('Vanguard\Http\Requests\Mercancia\CreateMercanciaRequest', '#mercancia-form') !!}
@stop