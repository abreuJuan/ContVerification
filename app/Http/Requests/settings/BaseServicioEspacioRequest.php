<?php

namespace Vanguard\Http\Requests\settings;

use Vanguard\Http\Requests\Request;

class BaseServicioEspacioRequest extends Request
{
    /**
     * Validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'lun.required' => trans('app.lun_required'),
            'mar.required' => trans('app.mar_required'),
            'mie.required' => trans('app.mie_required'),
            'jue.required' => trans('app.jue_required'),
            'vie.required' => trans('app.vie_required'),
            'sab.required' => trans('app.sab_required'),
            'dom.required' => trans('app.dom_required'),
            'lun_hora_fin.greater_than_field' => trans('app.gt_field'),
            'mar_hora_fin.greater_than_field' => trans('app.gt_field'),
            'mie_hora_fin.greater_than_field' => trans('app.gt_field'),
            'jue_hora_fin.greater_than_field' => trans('app.gt_field'),
            'vie_hora_fin.greater_than_field' => trans('app.gt_field'),
            'sab_hora_fin.greater_than_field' => trans('app.gt_field'),
            'dom_hora_fin.greater_than_field' => trans('app.gt_field'),
            
        ];
    }
}