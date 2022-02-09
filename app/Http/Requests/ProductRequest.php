<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stmpdh_art'    => 'required|max:255',
            'use'           => 'required',
            'codigo_ima'    => 'required',
            'stmpdh_tex'    => 'required',
            'precio'        => 'required',
            'web_marcas'    => 'required',
            'subparte'      => 'required',
            'parte'         => 'required',
            'modelo_anio'   => 'required',
            'cantminvta'    => 'required|numeric',
            'fecha_ingr'    => 'required|date',
            'nro_original'  => 'nullable',
            'stock_mini'    => 'required|numeric',
            'liquidacion'   => 'required',
            'max_ventas'    => 'required|numeric',
            'test'          => 'nullable|boolean'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if ($validator->fails()) {

            throw new HttpResponseException(response(
                array(
                    'error' => true,
                    'status' => 422,
                    'message' => 'Hay un error en los datos enviados',
                    'fails' => $validator->errors()
                ),
                422
            ));

        }
    }
}
