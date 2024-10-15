{!! Form::open(['route' => 'conf_dia_feriado.store',  'id' => 'conf-dia-form']) !!}


<div class='row'>
<div class="col-md-4 mt-2 mt-md-0">
   <div class="form-group">

       <input type="text" class="form-control input-solid" name="hollydate" id="hollydate"  placeholder="D&iacute;a feriado" 
              autocomplete="off">
   </div>
</div>

<div class="col-md-4 mt-2 mt-md-0">
   <div class="form-group">

       <input type="text" class="form-control input-solid" name="descripcion" id="descripcion"  placeholder="Descripci&oacute;n" 
              autocomplete="off">
   </div>
</div>


 <div class="col-md-2 mt-2 mt-md-0">
    <div class="form-group">
        
    <button class="btn btn-primary" type="submit" >
             @lang('app.conf_dia_feriado_submit')              
    </button>
    </div>
 </div>
    
</div>

{!! Form::close() !!}

@section('scripts')
    {!! JsValidator::formRequest('Vanguard\Http\Requests\settings\DiaFeriadoRequest', '#conf-dia-form') !!}
    
    <script>
         $("#hollydate").datepicker({
    orientation: 'bottom',
    startView: 'years',
    format: 'yyyy-mm-dd'
});
    </script>
@stop