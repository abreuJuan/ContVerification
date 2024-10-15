<?php

namespace Vanguard\Http\Requests\Solicitud;

use Vanguard\Http\Requests\Request;

class solicitudCondicionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request. FilterRequest solicitudCondicionRequest
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'condicion' => 'required',
        ];


        return $rules;
    }
}
