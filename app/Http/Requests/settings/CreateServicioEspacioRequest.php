<?php

namespace Vanguard\Http\Requests\settings;



class CreateServicioEspacioRequest extends BaseServicioEspacioRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [            
            'lun' => 'required',            
            'mar' => 'required',
            'mie' => 'required',
            'jue' => 'required',
            'vie' => 'required',
            'sab' => 'required',
            'dom' => 'required',
            "lun_hora_inicio" => "nullable",
            "lun_hora_fin" => "nullable|greater_than_field:lun_hora_inicio",
            "mar_hora_inicio" => "nullable",
            "mar_hora_fin" => "nullable|greater_than_field:mar_hora_inicio",
            "mie_hora_inicio" => "nullable",
            "mie_hora_fin" => "nullable|greater_than_field:mie_hora_inicio",
            "jue_hora_inicio" => "nullable",
            "jue_hora_fin" => "nullable|greater_than_field:jue_hora_inicio",
            "vie_hora_inicio" => "nullable",
            "vie_hora_fin" => "nullable|greater_than_field:vie_hora_inicio",
            "sab_hora_inicio" => "nullable",
            "sab_hora_fin" => "nullable|greater_than_field:sab_hora_inicio",
            "dom_hora_inicio" => "nullable",
            "dom_hora_fin" => "nullable|greater_than_field:dom_hora_inicio",
        ];
    }
}
