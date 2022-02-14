<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class VisitsFormRequest extends FormRequest
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
            'id_usuario_creo' => 'required|integer',
            'id_visitante' => 'required|integer',
            'placa_principal' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'id_usuario_creo.required' => 'El campo usuario creo es obligatorio',
            'id_usuario_creo.integer' => 'El campo usuario creo debe de ser un numero entero',
            'id_visitante.required' => 'El campo visitante es obligatorio',
            'id_visitante.integer' => 'El campo visitante debe de ser un numero entero',
            'placa_principal.required' => 'El campo placa principal es obligatorio',
            'placa_principal.boolean' => 'El campo placa principal debe de ser un booleano true/false',
        ];
    }

    /**
     * Return validation errors as json response
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $response = [
            'status' => 'failure',
            'status_code' => 400,
            'message' => 'Bad Request',
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 400));
    }
}
