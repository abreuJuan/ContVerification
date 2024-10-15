<div class="row">
    
    <div class="col-md-12">
        
        <table class="table table-bordered table-condensed">
            <tr>
                <th>@lang('app.solicitud_list_title_nombre_completo'):</th>
                <td>{{$solicitante->nombre_completo}}</td>
                <th>@lang('app.tipo_documento'):</th>
                <td>{{isset($tipoDocumento->descripcion)?$tipoDocumento->descripcion:""}}</td>
            </tr>
            
            <tr>
                
                  <th>@lang('app.email_solicitante'):</th>
                  <td>{{$solicitante->email}}</td>
                  <th>@lang('app.solicitud_list_title_documento'):</th>
                  <td>{{$solicitante->documento}}</td>

            </tr>
            <tr>
                  <th>@lang('app.solicitante_phone'):</th>
                  <td>{{$solicitante->telefono}}</td>
            </tr>
            
            
            
        </table>
        
    </div>
    
</div>