 <tr>                         
        <td>{{$visitante->nombre_completo}}</td>
        <td>{{$visitante->tipo_documento}}</td>
        <td>{{$visitante->documento}}</td>
        <td>{{$visitante->contenedor}}</td>
        <td>{{$visitante->rol==1?"Representante":"Ayudante"}}</td>
        <td>{{$visitante->estatus}}</td>
        <td class="text-center align-middle">    

        <a href="{{ route('visitante.delete', $visitante->id) }}"
           class="btn btn-icon"

           data-toggle="tooltip"
           data-placement="top"
           data-method="DELETE"
           data-confirm-title="@lang('app.please_confirm')"
           data-confirm-text="@lang('app.are_you_sure_delete_visitor')"
           data-confirm-delete="@lang('app.yes_delete_him')">
            <i class="fas fa-trash"></i>
        </a>
    </td>
 </tr>