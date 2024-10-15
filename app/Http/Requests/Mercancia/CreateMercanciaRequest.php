<?php

namespace Vanguard\Http\Requests\Mercancia;

class CreateMercanciaRequest extends BaseMercanciaRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'descripcion' => 'required|unique:mercancias,descripcion'
        ];
    }
}
