<?php

namespace Vanguard\Http\Requests\visitaPermitida;

class CreateVisitaPermitidaRequest extends BaseVisitaPermitidaRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cantidad' => 'required'
        ];
    }
}
