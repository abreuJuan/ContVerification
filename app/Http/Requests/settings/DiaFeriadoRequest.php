<?php

namespace Vanguard\Http\Requests\settings;

use Vanguard\Http\Requests\Request;

class DiaFeriadoRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [            
            'hollydate' => 'required|date',
            'descripcion' => 'required'

        ];
    }
}
