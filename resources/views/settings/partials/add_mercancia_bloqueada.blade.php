{!! Form::open(['route' => 'mercancia.bloqueada.store',  'id' => 'mercancia-bloqueada-form']) !!}


<div class='row'>
<div class="col-md-2 mt-2 mt-md-0">
   <div class="form-group">
         <label for="Mercancia">@lang('app.mercancia_blocked_field')</label>
         <select class="form-control" name="descripcion[]" id="descripcion" multiple="">
        <option></option>        
    @foreach($mercancias_list as $mercancia_item)
      <option value="{{$mercancia_item->id}}">
              {{$mercancia_item->descripcion}}
      </option>
    @endforeach
  </select>
   </div>
</div>



 <div class="col-md-2 mt-2 mt-md-0">
    <div class="form-group">
        <br/>
    <button class="btn btn-primary" type="submit" >
             @lang('app.mercancia_blocked_submit')              
    </button>
    </div>
 </div>
    
</div>

{!! Form::close() !!}

@section('scripts')
    {!! JsValidator::formRequest('Vanguard\Http\Requests\MercanciaBloqueo\CreateMercanciaBloqueoRequest', '#mercancia-bloqueada-form') !!}
    
    <script>
         $("#descripcion").select2({
             placeholder: "Selecione una o más mercancías"
             
         })
    </script>
@stop