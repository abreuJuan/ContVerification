
<div class='row'>
    
    <div class='col-md-6'>

      <div class="form-group">
        <label for="servicio">@lang('app.service_label')</label>
        <select name="servicio" class="form-control input-solid" id="servicio">
            <option value=""></option>
            @foreach($servicios AS $x)
            <option value="{{$x->id}}" 
                @if (Input::has('servicio') && Input::get('servicio') != '' && Input::get('servicio') == $x->id) 
                    selected="selected" 
                @endif >
                {{$x->descripcion}}
            </option>        
            @endforeach
        </select>

      </div> 

      <div class="form-group">
        <label for="mercancia">@lang('app.mercancia')</label>

       <select name="mercancia" class="form-control input-solid" id="mercancia">
            <option value=""></option>
            @foreach($mercancias AS $x)
            <option value="{{$x->id}}"
               @if (Input::has('mercancia') && Input::get('mercancia') != '' && Input::get('mercancia') == $x->id) 
                    selected="selected" 
                @endif >
                {{$x->descripcion}}
            </option>        
            @endforeach
        </select>
      </div>   



    </div>

    <div class='col-md-6'>

      <div class="form-group">
        <label for="condicion">@lang('app.condicion')</label>    

        <select name="condicion" class="form-control input-solid" id="condicion">
            <option value=""></option>
            @foreach($condiciones AS $x)
            <option value="{{$x}}"
                @if (Input::has('condicion') && Input::get('condicion') != '' && Input::get('condicion') == $x) 
                    selected="selected" 
                @endif >
                {{$x}}
            </option>        
            @endforeach
        </select>
      </div>  

        
        
       <div class="form-group">
        <label for="estados">@lang('app.status')</label>    

        <select name="statusId" class="form-control input-solid" id="status">
            <option value=""></option>
            @foreach($estados AS $x)
            <option value="{{$x->id}}"
                    @if (Input::has('statusId') && Input::get('statusId') != '' && Input::get('statusId') == $x->id) 
                    selected="selected" 
                @endif >
                {{$x->description}}
            </option>        
            @endforeach
        </select>
      </div>    

    </div>

    <div class="col-md-6">
      
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="allow_scanner" name="allow_scanner" @if( Input::has('allow_scanner') ) checked @endif>
            <label class="form-check-label">Esc&aacute;ner</label>
        </div>
        
    </div>
    <p>&nbsp;</p>
    
</div>

