<?php

namespace Vanguard\Http\Requests\Mercancia;

use Vanguard\Http\Requests\Request;

class BaseMercanciaRequest extends Request
{
    /**
     * Validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'descripcion.unique' => trans('app.mercancia_already_exists'),
            'descripcion.required' => trans('app.mercancia_required')
        ];
    }
}