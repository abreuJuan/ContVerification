<?php

namespace Vanguard\Http\Requests\MercanciaBloqueo;

class CreateMercanciaBloqueoRequest extends BaseMercanciaBloqueoRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'descripcion' => 'required'
        ];
    }
}
