<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EmpleadoHorarioFormRequest extends FormRequest
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
            'id_empleado' => 'required',
            'nombre' => 'required',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id_empleado.required' => 'El campo Empleado es obligatorio',
            'nombre.required' => 'El campo :attribute esta obligatorio',
            'hora_inicio.required' => 'El campo Hora Inicio esta obligatorio',
            'hora_fin.required' => 'El campo Hora Fin esta obligatorio',
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
