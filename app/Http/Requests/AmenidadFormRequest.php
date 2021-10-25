<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AmenidadFormRequest extends FormRequest
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
            'id_condominio' => 'required',
            'nombre' => 'required',
            'descripcion' => 'required',
            'color' => 'required',
            'estado' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_condominio.required' => 'El campo condominio es obligatorio',
            'nombre.required' => 'El campo :attribute es obligatorio',
            'descripcion.required' => 'El campo :attribute es obligatorio',
            'color.required' => 'El campo :attribute es obligatorio',
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
