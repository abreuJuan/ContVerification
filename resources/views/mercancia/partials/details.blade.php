

<div class="form-group">
 <label for="Servio">@lang('app.mercancia_field')</label>
 <input type="text" name="descripcion" class="form-control" 
        placeholder="@lang('app.mercancia_field')" value="{{ $edit ? $mercancia->descripcion : '' }}"/>
</div>