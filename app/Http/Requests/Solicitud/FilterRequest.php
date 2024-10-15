<?php

namespace Vanguard\Http\Requests\Solicitud;

use Vanguard\Http\Requests\Request;

class FilterRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'documento_solicitante' => 'nullable|numeric|digits_between:7,11',
        ];


        return $rules;
    }
}
