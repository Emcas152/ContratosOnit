<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CondominioFormRequest extends FormRequest
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
            'nombre' => 'required',
            'parqueo_general' => 'required|integer',
            'parqueo_visitantes' => 'required|integer',
            'estado' => 'required',
            'id_usuario' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El campo :attribute es obligatorio',
            'parqueo_general.required' => 'El campo parqueo general es obligatorio',
            'parqueo_general.integer' => 'El campo parqueo general debe de ser un numero entero',
            'parqueo_visitantes.required' => 'El campo :attribute es obligatorio',
            'parqueo_visitantes.integer' => 'El campo parqueo visitantes debe de ser un numero entero',
            'estado.required' => 'El campo :attribute es obligatorio',
            'id_usuario.required' => 'El campo usuario es obligatorio',
            'id_usuario.integer' => 'El campo usuario debe de ser un numero entero',
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
