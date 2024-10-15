 <tr>                 
        <td class="text-center ">
            <input type='checkbox' name="solicitud_selecionadas[]" class='solicitud_selecionadas' value='{{$solicitud->id}}'
                   onchange="showStatusPanel()" onleave='showStatusPanel()'/>
        </td>
        <td>{{$solicitud->bl}}</td>
        <td>{{$solicitud->servicio}}</td>
        <td>{{$solicitud->mercancia}}</td>
        <td>{{$solicitud->consignatario}}</td>
        <td>{{$solicitud->condicion}}</td>
        <td>{{$solicitud->allow_scanner}}</td>
        <td>{{$solicitud->fecha_verificar}}</td>
        <td>{{$solicitud->hora_verificar}}</td>
       <!-- <td>{{$solicitud->nombre_completo}}</td>
        <td>{{$solicitud->documento}}</td>-->
        <td>{{$solicitud->createdDate}}</td>   
        <td>
            <a href="{{route("solicitante.view",$solicitud->individuo_id)}}">
           
            <i class="fa fa-eye"></i>
            </a>
        </td>
        <td>
            <a href="{{route("visitante.index",$solicitud->id)}}">
                {{$solicitud->cantidadVisitantes}}
                <i class="fa fa-eye"></i>
            </a>
        </td>
        <td>
            <a href="{{route("contenedor.index",$solicitud->id)}}">
            {{$solicitud->cantidadContenedores}}
            <i class="fa fa-eye"></i>
            </a>
        </td>
        
        <td>
            <a href="{{route("solicitud.comentario",$solicitud->id)}}">            
            <i class="fa fa-eye"></i>
            </a>
        </td>
        
        <td>{{$solicitud->email}}</td>
        <td>{{$solicitud->estado}}</td>
        <td>{{$solicitud->modificadoPor}}</td>
        
        <td class="text-center align-middle">    

        <a href="{{ route('solicitud.delete', $solicitud->id) }}"
           class="btn btn-icon"

           data-toggle="tooltip"
           data-placement="top"
           data-method="DELETE"
           data-confirm-title="@lang('app.please_confirm')"
           data-confirm-text="@lang('app.are_you_sure_delete_request')"
           data-confirm-delete="@lang('app.yes_delete_him')">
            <i class="fas fa-trash"></i>
        </a>
    </td>
    
    
 </tr>
 
 @section('scripts')
 
 <script type="text/javascript">
 
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
            +"&mercancia="+mercancia+"&condicion="+condicion+"&statusId="+status+"&allow_scanner="+allow_scanner,"__blank");

}
 
 
 </script>
 
 @stop