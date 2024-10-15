<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:o="urn:schemas-microsoft-com:office:office"
    style="width: 100%; font-family: 'open sans', 'helvetica neue', helvetica, arial, sans-serif; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; padding: 0; margin: 0;"
>
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="x-apple-disable-message-reformatting" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="telephone=no" name="format-detection" />
        <title>Nueva plantilla de correo electrónico 2020-12-14</title>
        <!--[if (mso 16)]>
            <style type="text/css">
                a {
                    text-decoration: none;
                }
            </style>
        <![endif]-->
        <!--[if gte mso 9]>
            <style>
                sup {
                    font-size: 100% !important;
                }
            </style>
        <![endif]-->
        <!--[if gte mso 9]>
            <xml>
                <o:OfficeDocumentSettings> <o:AllowPNG> </o:AllowPNG> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings>
            </xml>
        <![endif]-->
        <!--[if !mso]><!-- -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <!--<![endif]-->

        <style>
            @media print {            
              .noprint {
                display: none;
              }
            }
        </style>

    </head>
    <body style="padding:10px;">

     <div class="row">
        <div class="col-md-12">

            <div class="alert" style="background-color: #0A3C71;color:white;">
                <h2>
                Agregar o Editar acceso a la zona de verificación
                </h2>
            </div>
        </div>
     </div>
     <div class="row alert">
    {{ Form::open(array('url' => route('solicitud.cambiar_acceso_zona_verificacion'),"method" => "post", 'id' => 'form_solicitud')) }}
        <div class="col-md-12">
         <input type="hidden" name="hash" id="hash" value="{{$hash}}"/>
            <p>&nbsp;</p>
            <div class="form-group brdbot">
                <h5>Datos del representante</h5>
            </div>

            <div class="mb-12 row">

                <div class="col-md-3">
                    <label for="staticEmail" class="col-form-label">Nombre y apellido</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control"  name="rnombre"
                     id="rnombre"                     
                     value="{{isset($responsable->nombre_completo) ? $responsable->nombre_completo : ""}}">
                    </div>
               </div>

               <div class="col-md-3">
                <label for="staticEmail" class="col-form-label">Tipo de documento</label>
                <div class="col-sm-10">
                    <select name="rpasaporteCedula" id="rpasaporteCedula" class="form-control"                   
                     >
                        <option value="Cédula" @if( isset( $responsable->tipo_documento ) && $responsable->tipo_documento == 1 ) selected  @endif >C&eacute;dula</option>
                        <option value="Pasaporte" @if( isset( $responsable->tipo_documento ) && $responsable->tipo_documento == 2 ) selected  @endif >Pasaporte</option>
                    </select>	
                </div>
               </div>

               <div class="col-md-3">
                <label for="staticEmail" class="col-form-label">Documento</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="rdocumento" name="rdocumento"
                    
                    value="{{isset($responsable->documento) ? $responsable->documento : ""}}">
                </div>
               </div>

               
               <div class="col-md-3">
                <label for="staticEmail" class="col-form-label"></label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext
                     @if( isset( $responsable->estatus ) && $responsable->estatus == "Pendiente huella" ) alert alert-warning 
                     @elseif( isset( $responsable->estatus ) && $responsable->estatus == "Sin restricción" ) alert alert-success 
                     @else  @endif"
                     id="restatus" name="restatus" 
                    value="{{isset($responsable->estatus) ? $responsable->estatus : ""}}">
                </div>
               </div>

             </div>

             <p>&nbsp;</p>
            
             <div class="form-group brdbot">
                <h5>Datos de los ayudantes por contenedor</h5>
                <p>Cantidad de contenedores: {{count($contenedores)}}</p>
            </div>
 
            @foreach( $contenedores as $contenedor )

            @php 
                
                $ayudantesContenedor = isset( $ayudantes[$contenedor->contenedor] ) 
                 ? $ayudantes[$contenedor->contenedor] : [];
               
            @endphp
            <div class="col-md-12 row">
                <table class="table contenedor-ayudante">
                   <thead>
                      <tr>
                        <th>Contenedor</th>
                        <th colspan="2">
                            <input type='text' class="form-control-plaintext containers" 
                            value="{{$contenedor->contenedor}}" id="{{$contenedor->contenedor}}"/></th>
                      </tr>
                      <tr>
                        <th>Nombre y Apellido</th>
                        <th>Tipo de documento</th>
                        <th>Documento</th>
                        <th></th>
                      </tr>
                   </thead>
                   <tbody>
                     @for( $i = 0; $i < 4; $i++)
                       <tr>
                         <td>
                            <input type="text" class="form-control nombre-ayudante" 
                            id="ynombre_{{$i}}_{{$contenedor->contenedor}}" 
                            name="ynombre_{{$i}}_{{$contenedor->contenedor}}" 
                            data-index="{{$i}}" data-contenedor="{{$contenedor->contenedor}}"
                            value="{{ isset($ayudantesContenedor[$i]["nombre_completo"] ) ? $ayudantesContenedor[$i]["nombre_completo"] : ""}}"
                            
                            >
                         </td>
                         <td>
                            <select name="ypasaporteCedula_{{$i}}_{{$contenedor->contenedor}}"
                                id="ypasaporteCedula_{{$i}}_{{$contenedor->contenedor}}"  
                                data-index="{{$i}}" data-contenedor="{{$contenedor->contenedor}}"                              
                                class="form-control pasaporteCedula-ayudante ">
                                <option value="Cédula" 
                                  @if( isset($ayudantesContenedor[$i]["tipo_documento"] ) && $ayudantesContenedor[$i]["tipo_documento"] == 1)
                                  selected
                                  @endif
                                  >C&eacute;dula</option>
                                <option value="Pasaporte"
                                @if( isset($ayudantesContenedor[$i]["tipo_documento"] ) && $ayudantesContenedor[$i]["tipo_documento"] == 2 )
                                selected
                                @endif
                                >Pasaporte</option>
                            </select>	
                         </td>
                         <td>
                            <input type="text" class="form-control documento-ayudante" 
                             id="ydocumento_{{$i}}_{{$contenedor->contenedor}}"
                             name="ydocumento_{{$i}}_{{$contenedor->contenedor}}" 
                             data-index="{{$i}}" data-contenedor="{{$contenedor->contenedor}}"
                             value="{{ isset($ayudantesContenedor[$i]["documento"] ) ? $ayudantesContenedor[$i]["documento"] : ""}}"
                              >
                         </td>
                         <td>
                            <input type="text" readonly 
                            class="form-control-plaintext
                            @if( isset( $ayudantesContenedor[$i]["estatus"] ) && $ayudantesContenedor[$i]["estatus"] == "Pendiente huella" ) alert alert-warning 
                            @elseif( isset( $ayudantesContenedor[$i]["estatus"] ) && $ayudantesContenedor[$i]["estatus"] == "Sin restricción" )  alert alert-success 
                            @endif"
                             id="yestado_{{$i}}_{{$contenedor->contenedor}}"
                             name="yestado_{{$i}}_{{$contenedor->contenedor}}" 
                             data-index="{{$i}}" data-contenedor="{{$contenedor->contenedor}}"
                             value="{{ isset( $ayudantesContenedor[$i]["estatus"] ) ? $ayudantesContenedor[$i]["estatus"] : ""}}">
                         </td>
                       </tr>

                     @endfor
   
                   </tbody>

                </table>            

            </div>
            @endforeach

        <div class="row noprint"> 
            <div class="col-md-12">
                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="btnGuardar" onclick="save();">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save-fill" viewBox="0 0 16 16">
                            <path d="M8.5 1.5A1.5 1.5 0 0 1 10 0h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h6c-.314.418-.5.937-.5 1.5v7.793L4.854 6.646a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l3.5-3.5a.5.5 0 0 0-.708-.708L8.5 9.293V1.5z"/>
                          </svg>  
                        Guardar
                    </button>
                    <button type="button" class="btn btn-default" id="btnPrint" onclick="window.print();">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                          </svg>  Imprimir
                    </button>
                </div>
        </div>
            
        </div>

        <input type="hidden" name="fields" id="fields" value=""/>

     </div>
    {!! Form::close() !!}

    </body>

<script type="text/javascript">

  window.onload = function() {
    validarField();
  }

    function validarField() 
    {
        // Obtener elementos del formulario
        const nombreRepresentante = document.getElementById('rnombre');
        const documentoRepresentante = document.getElementById('rdocumento');
        const pasaporteCedulaRepresentante = document.getElementById('rpasaporteCedula');
        const estadoRepresentante = document.getElementById('restatus');
        const contenedorAyudantes = document.querySelectorAll('.contenedor-ayudante');

        // Agregar eventos de unfocus (blur)
        nombreRepresentante.addEventListener('blur', function() {
        validarCampo(nombreRepresentante, 'El nombre del representante es obligatorio.',documentoRepresentante);
        });

        documentoRepresentante.addEventListener('blur', function() {
        validarCampo(documentoRepresentante, 'El documento del representante es obligatorio.',pasaporteCedulaRepresentante,null,
         getImpedimento,estadoRepresentante);
        
        });

        pasaporteCedulaRepresentante.addEventListener('blur', function() {
        validarCampo(documentoRepresentante, 'El documento del representante es obligatorio.',pasaporteCedulaRepresentante,null, getImpedimento,
        estadoRepresentante);
        
    });

        
        var nombreAyudante = document.getElementsByClassName('nombre-ayudante');
        var documentoAyudante = document.getElementsByClassName('documento-ayudante');
        var pasaporteCedulaAyudante = document.getElementsByClassName('pasaporteCedula-ayudante');
        
        for (var i = 0; i < nombreAyudante.length; i++) {
            
        nombreAyudante[i].addEventListener('blur', function() {
            var dataIndex = this.getAttribute('data-index');
            var dataContenedor = this.getAttribute('data-contenedor');
            var documento = document.getElementById('ydocumento_'+dataIndex+'_'+dataContenedor);
            var nombre = document.getElementById('ynombre_'+dataIndex+'_'+dataContenedor);

            validarCampo(this, 'El nombre del ayudante es obligatorio.', documento,nombre);
        });
        }

        for (var i = 0; i < documentoAyudante.length; i++) {
        documentoAyudante[i].addEventListener('blur', function() {
            var dataIndex = this.getAttribute('data-index');
            var dataContenedor = this.getAttribute('data-contenedor');
            var pasaporteCedulaAyudante1 = document.getElementById('ypasaporteCedula_'+dataIndex+'_'+dataContenedor);
            var nombre = document.getElementById('ynombre_'+dataIndex+'_'+dataContenedor);
            var estado = document.getElementById('yestado_'+dataIndex+'_'+dataContenedor);

            validarCampo(this, 'El documento del ayudante es obligatorio.',
                         pasaporteCedulaAyudante1,nombre,getImpedimento,estado);
        });
       }

       for (var i = 0; i < pasaporteCedulaAyudante.length; i++) {
        pasaporteCedulaAyudante[i].addEventListener('blur', function() {
            var dataIndex = this.getAttribute('data-index');
            var dataContenedor = this.getAttribute('data-contenedor');
            var documentoAyudante = document.getElementById('ydocumento_'+dataIndex+'_'+dataContenedor);
            var estado = document.getElementById('yestado_'+dataIndex+'_'+dataContenedor);

            validarCampo(documentoAyudante, 'El documento del ayudante es obligatorio.',this,null
            ,getImpedimento,estado);
        });
       }


      

    }

// Función para mostrar mensaje de alerta debajo del elemento
function mostrarAlerta(elemento, mensaje) {
  const alerta = document.createElement('div');
  alerta.textContent = mensaje;
  alerta.style.color = 'red';
  alerta.style.marginTop = '5px';
  
  elemento.parentNode.appendChild(alerta);
}

// Función para validar el nombre y el documento
function validarCampo(elemento, mensaje, elemento2, elemento3, fnc, estadoElemento ) {
   removerAlerta( elemento );

  if (!elemento.value && elemento2 != null && elemento2.value) {
    
    if( elemento3 != null && !elemento3.value )
    {
        
        removerAlerta( elemento3 );
    }
    else
    {
        mostrarAlerta(elemento, mensaje);
    } 
  }
  else if( elemento2 != null && elemento2.value == "Cédula" && elemento.value.length !== 11)
  {
    mostrarAlerta(elemento, 'El documento debe contener 11 dígitos.');
  }
  else if( elemento2 != null && elemento2.value === 'Cédula' && !/^\d+$/.test(elemento.value)) {
    mostrarAlerta(elemento, 'El documento debe contener solo números.');
  } 
  else {
    removerAlerta( elemento );
    if( fnc != null )
    fnc(elemento.value, elemento2.value, estadoElemento);
  }
}

function removerAlerta( elemento )
{
    var alerta = elemento.parentNode.getElementsByTagName('div');
    
    for( var i = alerta.length; i >= 0; i--)
    {
      if (alerta[i]) {
       alerta[i].remove();
      }
    }
}

function validarDatos() {
  const { representante, containers } = getFields();

  // Validación de representante
  if (!representante.nombre || !representante.documento) {
    return "El nombre y el documento del representante son obligatorios.";
  }

  if (representante.pasaporteCedula === "Cédula" && !/^\d+$/.test(representante.documento)) {
    return "El documento del representante debe contener solo números.";
  }

  if ( representante.pasaporteCedula === "Cédula" && representante.documento.length !== 11) {
    return "El documento del representante debe contener 11 dígitos.";
  }

  // Validación de ayudantes en los containers
  for (const container of containers) {
   /* if (container.ayudantes.length === 0) {
      return "Debe llenar al menos un ayudante para cada contenedor.";
    }
*/
    for (const ayudante of container.ayudantes) {
      if (!ayudante.nombre || !ayudante.documento) {
        return "El nombre y el documento del ayudante son obligatorios.";
      }

      if (ayudante.pasaporteCedula === "Cédula" && !/^\d+$/.test(ayudante.documento)) {
        return "El documento del ayudante debe contener solo números.";
      }

      if ( ayudante.pasaporteCedula === "Cédula" && ayudante.documento.length !== 11) {
        return "El documento del ayudante debe contener 11 dígitos.";
      }
    }
  }

  // Si todas las validaciones pasan, se considera que los datos son válidos
  return  true;
}
  
  function getFields()
  {
      return {"representante": getRepresentante(), "containers": getContainers()};

  }

  function getContainers()
  {       
     var containers = document.getElementsByClassName("containers");
     var contenedores = [];
      
     for( var i = 0; i < containers.length; i++ )
     {     
        var ayudantes = getAyudantePorContenedor( containers[i].value );

        var contenedor = {
            contenedor: containers[i].value,
            ayudantes: ayudantes
        };

        contenedores.push(contenedor);
    }

    return contenedores;

  }

  function getAyudantePorContenedor( contenedor )
  {
      var ayudantes = [];

      for( var i = 0; i < 4; i++ )
      {
            var nombre = document.getElementById("ynombre_"+i+"_"+contenedor).value;
            var pasaporteCedula = document.getElementById("ypasaporteCedula_"+i+"_"+contenedor).value;
            var documento = document.getElementById("ydocumento_"+i+"_"+contenedor).value;
            var estatus = document.getElementById("yestado_"+i+"_"+contenedor).value;
    
            if( nombre == "" || documento == "" )            
                continue;
            
            var ayudante = {
                index: i,
                nombre: nombre,
                pasaporteCedula: pasaporteCedula,
                documento: documento,
                estatus: estatus
            };
    
            ayudantes.push(ayudante);
      }

      return ayudantes;
  }

  

  function getRepresentante()
  {
        var nombre = document.getElementById("rnombre").value;
        var pasaporteCedula = document.getElementById("rpasaporteCedula").value;
        var documento = document.getElementById("rdocumento").value;
        var estatus = document.getElementById("restatus").value;
    
        var representante = {
            nombre: nombre,
            pasaporteCedula: pasaporteCedula,
            documento: documento,
            estatus: estatus
        };
    
        return representante;
  }

   function submitValidate()
   {
     if( document.getElementById("condicion").value == 0)
     {
         alert(" El campo condición es requerido.");
         return false;
     }

     document.getElementById("solicitudForm").submit();

   }

   function getImpedimento(documento, tipo, estadoElemento)
   {
      var url = new URL(window.location.href);
      const domainWithProtocol = url.origin;

        url = `${domainWithProtocol}/api/impedimento/${documento}/${tipo}`;

        fetch(url)
        .then(response => {
            if (response.ok) {
            return response.json();
            } else {
            throw new Error('Request failed');
            }
        })
        .then(data => {
            // Process the received data
            estadoElemento.removeAttribute("class");

            if( data.Impedimento == 1 )
            {
                alert("Distinguido Cliente, cortésmente le informamos que debe presentarse a la oficina de control de acceso para revisar su estatus");
                estadoElemento.value = "Impedimento";
                estadoElemento.setAttribute("class", "form-control-plaintext alert alert-danger");
            }
            else if( data.Impedimento != 1 && data.Sello == 0)
            {
                alert("Distinguido Cliente, cortésmente le informamos que debe completar el proceso establecido de registro. Por favor presentarse a la oficina de control de acceso");
                estadoElemento.value = "Pendiente huella";
                estadoElemento.setAttribute("class", "form-control-plaintext alert alert-warning");
            }
            else
            {
                estadoElemento.value = "Sin restricción";
                estadoElemento.setAttribute("class", "form-control-plaintext alert alert-success");
            }
            console.log(data);
        })
        .catch(error => {
            // Handle the error
            console.error(error);
        });

   }

   function save()
   {
      var messaje = validarDatos();

        if( messaje !== true )
        {
            alert(messaje);
            return;
        }

        document.getElementById("fields").value = JSON.stringify(getFields());
       
      document.getElementById("form_solicitud").submit();
   }

</script>

</html>