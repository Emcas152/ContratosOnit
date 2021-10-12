<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SolicitudAccesorioFormRequest extends FormRequest
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
            'id_accesorio' => 'required|integer',
            'id_usuario_solicito' => 'required|integer',
            'id_usuario_creo' => 'required|integer',
            'id_autorizo' => 'required|integer',
            'fecha_solicitud' => 'required',
            'estado' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_accesorio.required' => 'El campo accesorio es obligatorio',
            'id_accesorio.integer' => 'El campo accesorio debe de ser un numero entero',
            'id_usuario_solicito.required' => 'El campo solicito es obligatorio',
            'id_usuario_solicito.integer' => 'El campo solicito debe de ser un numero entero',
            'id_usuario_creo.required' => 'El campo usuario creo es obligatorio',
            'id_usuario_creo.integer' => 'El campo usuario creo debe de ser un numero entero',
            'id_autorizo.required' => 'El campo autorizo es obligatorio',
            'id_autorizo.integer' => 'El campo autorizo debe de ser un numero entero',
            'fecha_solicitud.required' => 'El campo :attribute es obligatorio',
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
