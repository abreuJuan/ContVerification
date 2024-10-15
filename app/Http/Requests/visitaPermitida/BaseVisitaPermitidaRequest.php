<?php

namespace Vanguard\Http\Requests\visitaPermitida;

use Vanguard\Http\Requests\Request;

class BaseVisitaPermitidaRequest extends Request
{
    /**
     * Validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cantidad.required' => trans('app.cantidad_required_field')
        ];
    }
}