 <tr>                         
        <td>{{$mercancia->descripcion}}</td>
        <td>{{$mercancia->createdDate}}</td>                             
        <td class="text-center align-middle">        


        <a href="{{ route('mercancia.edit', $mercancia->id) }}"
           class="btn btn-icon edit"
           title="@lang('app.edit_mercancia')"
           data-toggle="tooltip" data-placement="top">
            <i class="fas fa-edit"></i>
        </a>   

        <a href="{{ route('mercancia.delete', ["mercancia"=>$mercancia->id]) }}"
           class="btn btn-icon"

           data-toggle="tooltip"
           data-placement="top"
           data-method="DELETE"
           data-confirm-title="@lang('app.please_confirm')"
           data-confirm-text="@lang('app.are_you_sure_delete_mercancia')"
           data-confirm-delete="@lang('app.yes_delete_him')">
            <i class="fas fa-trash"></i>
        </a>
    </td>
 </tr>