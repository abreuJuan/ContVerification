               
                  
                  <div class="row">
                  
                  <div class='col-md-12'>
                    <div class='row'>
                    <div class="col-md-4 mt-2 mt-md-0">
                      <div class="form-group">
                          <label for="Servio" class='text-green'>@lang('app.servicio_espacio_lun')<span class="label_hora">@lang('app.servicio_espacio_selected_interval')</span></label>
                         <input type='number' name='lun' class="form-control" min="0" max="3" 
                                maxlength = "3" oninput="maxLengthCheck(this)" placeholder="@lang('app.allow_space_field')"
                                value="{{isset($espacioPorDias[0]["lun"])?$espacioPorDias[0]["lun"]:''}}"/>
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_inicio')</label>
                         <input type='time' name='lun_hora_inicio' class="form-control" placeholder="@lang('app.servicio_espacio_hora_inicio')"
                          value="{{isset($espacioPorDias[0]["lun_hora_inicio"])?$espacioPorDias[0]["lun_hora_inicio"]:''}}"      />
                         
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_fin')</label>
                         <input type='time' name='lun_hora_fin' class="form-control" placeholder="@lang('app.servicio_espacio_hora_fin')"
                             value="{{isset($espacioPorDias[0]["lun_hora_fin"])?$espacioPorDias[0]["lun_hora_fin"]:''}}"   />
                      </div>
                    </div>
                  
                    
                    <div class="col-md-4 mt-2 mt-md-0">
                      <div class="form-group">
                         <label for="Servio" class='text-green'>@lang('app.servicio_espacio_mar') <span class="label_hora">@lang('app.servicio_espacio_selected_interval')</span></label>
                         <input type='number' name='mar' class="form-control" min="0" max="3" maxlength = "3" 
                                oninput="maxLengthCheck(this)" placeholder="@lang('app.allow_space_field')"
                                value="{{isset($espacioPorDias[0]["mar"])?$espacioPorDias[0]["mar"]:''}}"/>
                         
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_inicio')</label>
                         <input type='time' name='mar_hora_inicio' class="form-control" placeholder="@lang('app.servicio_espacio_hora_inicio')"
                           value="{{isset($espacioPorDias[0]["mar_hora_inicio"])?$espacioPorDias[0]["mar_hora_inicio"]:''}}"     />
                         
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_fin')</label>
                         <input type='time' name='mar_hora_fin' class="form-control" placeholder="@lang('app.servicio_espacio_hora_fin')"
                            value="{{isset($espacioPorDias[0]["mar_hora_fin"])?$espacioPorDias[0]["mar_hora_fin"]:''}}"    />
                      </div>
                    </div>
                    
                   
                  
                    <div class="col-md-4 mt-2 mt-md-0">
                      <div class="form-group">
                         <label for="Servio" class='text-green'>@lang('app.servicio_espacio_mie') <span class="label_hora">@lang('app.servicio_espacio_selected_interval')</span></label>
                         <input type='number' name='mie' class="form-control" min="0" max="3" maxlength = "3" 
                                oninput="maxLengthCheck(this)" placeholder="@lang('app.allow_space_field')"
                                value="{{isset($espacioPorDias[0]["mie"])?$espacioPorDias[0]["mie"]:''}}"/>
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_inicio')</label>
                         <input type='time' name='mie_hora_inicio' class="form-control" placeholder="@lang('app.servicio_espacio_hora_inicio')"
                             value="{{isset($espacioPorDias[0]["mie_hora_inicio"])?$espacioPorDias[0]["mie_hora_inicio"]:''}}"    />
                         
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_fin')</label>
                         <input type='time' name='mie_hora_fin' class="form-control" placeholder="@lang('app.servicio_espacio_hora_fin')"
                             value="{{isset($espacioPorDias[0]["mie_hora_fin"])?$espacioPorDias[0]["mie_hora_fin"]:''}}"    />
                      </div>
                    </div>
                    </div>
                  

                  </div>
                  
                      
                   <hr/>
                  <div class='col-md-12'>
                      
                  <div class='row'>
                   <div class="col-md-4 mt-2 mt-md-0">
                      <div class="form-group">
                         <label for="Servio" class='text-green'>@lang('app.servicio_espacio_jue') <span class="label_hora">@lang('app.servicio_espacio_selected_interval')</span></label>
                         <input type='number' name='jue' class="form-control" min="0" max="3" maxlength = "3" oninput="maxLengthCheck(this)"  
                                placeholder="@lang('app.allow_space_field')"
                                value="{{isset($espacioPorDias[0]["jue"])?$espacioPorDias[0]["jue"]:''}}"/>
                         
                       <label for="Servio">@lang('app.servicio_espacio_hora_inicio')</label>
                         <input type='time' name='jue_hora_inicio' class="form-control" placeholder="@lang('app.servicio_espacio_hora_inicio')"
                            value="{{isset($espacioPorDias[0]["jue_hora_inicio"])?$espacioPorDias[0]["jue_hora_inicio"]:''}}"     />
                         
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_fin')</label>
                         <input type='time' name='jue_hora_fin' class="form-control" placeholder="@lang('app.servicio_espacio_hora_fin')"
                              value="{{isset($espacioPorDias[0]["jue_hora_fin"])?$espacioPorDias[0]["jue_hora_fin"]:''}}"   />
                      </div>
                    </div>
                      
                      
                      
                    <div class="col-md-4 mt-2 mt-md-0">
                      <div class="form-group">
                         <label for="Servio" class='text-green'>@lang('app.servicio_espacio_vie') <span class="label_hora">@lang('app.servicio_espacio_selected_interval')</span></label>
                         <input type='number' name='vie' class="form-control" min="0" max="3" maxlength = "3" 
                                oninput="maxLengthCheck(this)"  placeholder="@lang('app.allow_space_field')"
                                value="{{isset($espacioPorDias[0]["vie"])?$espacioPorDias[0]["vie"]:''}}"/>
                         
                         <label for="Servio">@lang('app.servicio_espacio_hora_inicio')</label>
                         <input type='time' name='vie_hora_inicio' class="form-control" placeholder="@lang('app.servicio_espacio_hora_inicio')"
                               value="{{isset($espacioPorDias[0]["vie_hora_inicio"])?$espacioPorDias[0]["vie_hora_inicio"]:''}}" />
                         
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_fin')</label>
                         <input type='time' name='vie_hora_fin' class="form-control" placeholder="@lang('app.servicio_espacio_hora_fin')"
                               value="{{isset($espacioPorDias[0]["vie_hora_fin"])?$espacioPorDias[0]["vie_hora_fin"]:''}}" />
                      </div>
                    </div>
                  
                  
                    <div class="col-md-4 mt-2 mt-md-0">
                      <div class="form-group" >
                         <label for="Servio" class='text-green'>@lang('app.servicio_espacio_sab') <span class="label_hora">@lang('app.servicio_espacio_selected_interval')</span></label>
                         <input type='number' name='sab' class="form-control" min="0" max="3" maxlength = "3" oninput="maxLengthCheck(this)" 
                                placeholder="@lang('app.allow_space_field')"
                                value="{{isset($espacioPorDias[0]["sab"])?$espacioPorDias[0]["sab"]:''}}"/>
                         
                         <label for="Servio">@lang('app.servicio_espacio_hora_inicio')</label>
                         <input type='time' name='sab_hora_inicio' class="form-control" placeholder="@lang('app.servicio_espacio_hora_inicio')"
                               value="{{isset($espacioPorDias[0]["sab_hora_inicio"])?$espacioPorDias[0]["sab_hora_inicio"]:''}}" />
                         
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_fin')</label>
                         <input type='time' name='sab_hora_fin' class="form-control" placeholder="@lang('app.servicio_espacio_hora_fin')"
                               value="{{isset($espacioPorDias[0]["sab_hora_fin"])?$espacioPorDias[0]["sab_hora_fin"]:''}}" />
                      </div>
                    </div>
                  </div>
                      
                    

                  </div>                       
                    
                   
                   <div class='col-md-12'>
                       
                     <div class='row'>
                           
                      
                     <div class="col-md-4 mt-2 mt-md-0">
                      <div class="form-group">
                         <label for="Servio" class='text-green'>@lang('app.servicio_espacio_dom') <span class="label_hora">@lang('app.servicio_espacio_selected_interval')</span></label>
                         <input type='number' name='dom' class="form-control" min="0" max="3" maxlength = "3" oninput="maxLengthCheck(this)" 
                                placeholder="@lang('app.allow_space_field')"
                                value="{{isset($espacioPorDias[0]["dom"])?$espacioPorDias[0]["dom"]:''}}"/>
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_inicio')</label>
                         <input type='time' name='dom_hora_inicio' class="form-control" placeholder="@lang('app.servicio_espacio_hora_inicio')"
                               value="{{isset($espacioPorDias[0]["dom_hora_inicio"])?$espacioPorDias[0]["dom_hora_inicio"]:''}}" />
                         
                         
                        <label for="Servio">@lang('app.servicio_espacio_hora_fin')</label>
                         <input type='time' name='dom_hora_fin' class="form-control" placeholder="@lang('app.servicio_espacio_hora_fin')"
                               value="{{isset($espacioPorDias[0]["dom_hora_fin"])?$espacioPorDias[0]["dom_hora_fin"]:''}}" />
                      </div>
                    </div>
                         
                     </div>  
                       
                   </div>   
                      
                  </div>    