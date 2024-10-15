@extends('layouts.app')

@section('page-title', trans('app.solicitud_title'))
@section('page-heading', trans('app.solicitud_subtitle'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
       @lang('app.solicitud_content_title')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        
       
        <form action="" method="GET" id="solicitud-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               id="search"
                               value="{{ Input::get('search') }}"
                               placeholder="Buscar por Bl, Contenedor, Consignatario, Documento solicitante o Nombre solicitante">

                            <span class="input-group-append">
                                @if (Input::has('search') && Input::get('search') != '')
                                    <a href="{{ route('solicitud.index') }}"
                                           class="btn btn-light d-flex align-items-center text-muted"
                                           role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i> {{old('search')}}
                                </button>
                            </span>
                    </div>
                </div>
                
                 <div class="col-md-2 mt-2 mt-md-0">
                        <input type="text"
                               class="form-control input-solid"
                               name="fechaDesde"
                               id="fechaDesde"
                               value="{{ $fechaDesde }}"
                               placeholder="Fecha desde" autocomplete="off">
                </div>
                
                <div class="col-md-2 mt-2 mt-md-0">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="fechaHasta"
                               id="fechaHasta"
                               value="{{ $fechaHasta }}"
                               placeholder="Fecha hasta" autocomplete="off">
                        
                           <span class="input-group-append">
 
                                <button class="btn btn-light" type="submit" id="search-users-btn" onclick="clearExport()">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>   


            </div>
            
          <p>     

        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="more_filters" onchange="showFilters()" name="more_filter" 
                   @if (Input::has('more_filter') && Input::get('more_filter') != '' && Input::get('more_filter') )
                     checked="checked"
                   @endif>
            <label class="form-check-label" for="more_filters">@lang('app.show_more_filters'):</label>
          </div>
        </p>
        
        
        <div id="filters">
           @include('solicitud.partials.more_filters',["servicios" => $servicios,"condiciones" => $condiciones,"mercancias" => $mercancias,"estados" => $estados])
           
           <button class="btn btn-primary">
                @lang('app.apply_filters')
           </button>
         </div>
       
        
        </form>
        

      
        {!! Form::open(['route' => 'solicitud.status', 'id' => 'status-form']) !!}
        <p>&nbsp;</p>
        <p>
            <b>@lang('app.solicitud_cantidad') <span class="badge badge-primary fs-14" > {{number_format($solicitudes->total(),0)}}</span></b>
            &nbsp;&nbsp;
            <b>@lang('app.solicitud_cantidad_contenedores') <span class="badge badge-success fs-14" >{{number_format($countContenedores,0)}} </span></b>
        </p>
        
        @permission(['solicitud.export'],false)
        <div class="row">
            <div class="col-12">

                <a href="javascript:void(0);" class="btn btn-default pull-right" onclick="exportTocsv()">
                    <i class="fa fa-address-book"></i> Exportar
                </a>

            </div>
        </div>
        @endpermission
        
        <p>&nbsp;</p>
        <div class='row' id='btn_change_status'>
            
         <div class='col-md-12'>   
             <div class='col-md-5'>
                 <b>@lang('app.status_to_change'):</b>
                 <br/>
                 <br/>
                 {!! Form::select('statusId', $statuses, Input::get('statusId'), ['id' => 'statusId', 'class' => 'form-control input-solid']) !!}                
             </div>  
             <div class='col-md-12'> 
                    <p>&nbsp;</p>
                  <button class='btn btn-primary' >@lang('app.change_status')</button>
             </div>
         </div>
            <p>&nbsp;</p>
        </div>
        
        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                <tr>
                    
                    <th style="vertical-align: initial;">@lang('app.selection')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_bl')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_servicio')</th>  
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_mercancia')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_cosignatario')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_condicion')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_solicitud_escanaer')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_fecha_verificar')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_hora_verificar')</th>
                   <!-- <th>@lang('app.solicitud_list_title_nombre_completo')</th>
                    <th>@lang('app.solicitud_list_title_documento')</th>-->
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_fecha_creacion_solicitud')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_details_solicitante')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_cantidad_visitante')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_list_title_cantidad_contenedores')</th>
                    <th style="vertical-align: initial;">@lang('app.solicitud_comentario')</th>
                    <th style="vertical-align: initial;">@lang('app.email')</th>
                    <th style="vertical-align: initial;">@lang('app.status')</th>
                    <th style="vertical-align: initial;">@lang('app.modifiedBy')</th>
                    <th class="text-center min-width-150" style="vertical-align: initial;">@lang('app.action') </th>
                </tr>
                </thead>
                <tbody>
                    @if (count($solicitudes))
                        @foreach ($solicitudes as $solicitud)
                             
                          @include('solicitud.partials.row')
                        
                        @endforeach
                    @else
                        <tr>
                            <td colspan="17"><em>@lang('app.no_records_found')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
      {!! Form::close() !!}
        
    </div>
</div>

{!! $solicitudes->render() !!}



@stop

@section('scripts')

<script>

  
  
 $("document").ready(function(){    
     showStatusPanel();
     showFilters();
     
    $('#fechaDesde').datepicker({
    orientation: 'bottom',
    startView: 'years',
    format: 'yyyy-mm-dd'
     });
     
    $('#fechaHasta').datepicker({
    orientation: 'bottom',
    startView: 'years',
    format: 'yyyy-mm-dd'
     });
 });
 
 function exportTocsv()
{
    var search = $("#search").val();
    var status = $("#status").val();
    var servicio = $("#servicio").val();
    var mercancia = $("#mercancia").val();
    var condicion = $("#condicion").val();
    var fechaDesde = $("#fechaDesde").val();
    var fechaHasta = $("#fechaHasta").val();
    var more_filters = $("#more_filters").val();
    var allow_scanner = $("#allow_scanner").val();

    window.open("/solicitud/export?search="+search+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta+"&more_filter="+more_filters+"&servicio="+servicio
            +"&mercancia="+mercancia+"&condicion="+condicion+"&statusId="+status,"&allow_scanner="+ allow_scanner,"__blank");

}
 
 function unCheckAll()
 {
    var checkboxes = document.getElementsByClassName('solicitud_selecionadas');
     
     if(checkboxes == null) return false;
     
     for(var i = 0; i < checkboxes.length; i++)
     {
         if(checkboxes[i].checked)
            checkboxes[i].checked = false;
     }
 }
     
 function showStatusPanel()
 {
     
     if(!isThereSelectionBox())
     {
         $('#btn_change_status').fadeOut(300);
         //document.getElementById("btn_change_status").style.display = 'none';
         return false;
     }
     
     $('#btn_change_status').fadeIn(300);
     //document.getElementById("btn_change_status").style.display = 'block';
 }
 
 function isThereSelectionBox()
 {
     var checkboxes = document.getElementsByClassName('solicitud_selecionadas');
     
     if(checkboxes == null) return false;
     
     for(var i = 0; i < checkboxes.length; i++)
     {
         if(checkboxes[i].checked)
             return true;
     }
     
     return false;
 }
 
 function showFilters()
{
    var filter = document.getElementById("more_filters");
    if(!filter.checked)
    {
        $("#filters").fadeOut(300);
        return false;
    }   
    
    $("#filters").fadeIn(300);
}
 

   
</script>

{!! JsValidator::formRequest('Vanguard\Http\Requests\Solicitud\FilterRequest', '#solicitud-form') !!}
@stop