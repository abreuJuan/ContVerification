<?php

namespace Vanguard\Http\Requests\MercanciaBloqueo;

use Vanguard\Http\Requests\Request;

class BaseMercanciaBloqueoRequest extends Request
{
    /**
     * Validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'descripcion.required' => trans('app.descripcion_required_field')
        ];
    }
}