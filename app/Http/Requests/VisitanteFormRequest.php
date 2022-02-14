<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class VisitanteFormRequest extends FormRequest
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
            'id_inquilino' => 'required|integer',
            'placa_vehiculo' => 'required',
            'dpi_visita' => 'required',
            'nombre_visita' => 'required',
            'estado' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_usuario_creo.required' => 'El campo usuario creo es obligatorio',
            'id_usuario_creo.integer' => 'El campo usuario creo debe de ser un numero entero',
            'id_inquilino.required' => 'El campo inquilino es obligatorio',
            'id_inquilino.integer' => 'El campo inquilino debe de ser un numero entero',
            'placa_vehiculo.required' => 'El campo :attribute es obligatorio',
            'nombre_visita.required' => 'El campo :attribute es obligatorio',
            'dpi_visita.required' => 'El campo :attribute es obligatorio',
            'estado.required' => 'El campo :attribute es obligatorio'
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
