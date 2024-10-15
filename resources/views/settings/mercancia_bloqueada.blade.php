@extends('layouts.app')

@section('page-title', trans('app.mercancia_title_blocked'))
@section('page-heading', trans('app.mercancia_subtitle_blocked'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
       @lang('app.mercancia_content_title_blocked')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        
        
       @include('settings.partials.add_mercancia_bloqueada')
        
        
       <p>&nbsp;</p>
       <p>&nbsp;</p>
        
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
                                    <a href="{{ route('mercancia.bloqueada') }}"
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
                    
                    <th class="min-width-80">@lang('app.mercancia_blocked_list_title')</th>
                    <th class="min-width-150">@lang('app.mercancia_blocked_list_date')</th>                
                    <th class="text-center min-width-150">@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                    @if (count($mercancias))
                        @foreach ($mercancias as $mercancia)
                             
                        <tr>         

                            
                            <td>{{$mercancia->descripcion}}</td>
                            <td>{{$mercancia->createdDate}}</td>                            
                            <td class="text-center align-middle">        

                            <a href="{{ route('mercancia.bloqueo.delete', $mercancia->id) }}"
                               class="btn btn-icon"
                               
                               data-toggle="tooltip"
                               data-placement="top"
                               data-method="DELETE"
                               data-confirm-title="@lang('app.please_confirm')"
                               data-confirm-text="@lang('app.are_you_sure_delete_mercancia_blocked')"
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

{!! $mercancias->render() !!}

@stop