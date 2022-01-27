<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class DetallePresupuestoFormRequest extends FormRequest
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
            'id_encabezado' => 'required|integer',
            'subcategoria' => 'required',
            'subtotal' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id_encabezado.required' => 'El campo :attribute creo es obligatorio',
            'id_encabezado.integer' => 'El campo :attribute creo debe de ser un numero entero',
            'subcategoria.required' => 'El campo :attribute es obligatorio',
            'subtotal.required' => 'El campo :attribute es obligatorio',
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
            'message' => 'solicitud incorrecta',
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 400));
    }
}
