<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;
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
            'email'     => 'nullable|email',
            'name'      => 'nullable',
            'start'     => 'nullable|date',
            'end'       => 'nullable|date',
            'discount'  => 'nullable|numeric'
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

            throw new ValidationException(response(
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
