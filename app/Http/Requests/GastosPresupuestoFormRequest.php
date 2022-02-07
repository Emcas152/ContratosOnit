<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class GastosPresupuestoFormRequest extends FormRequest
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
            'id_presupuesto' => 'required',
            'descripcion' => 'required',
            'numero_factura' => 'required',
            'serie_factura' => 'required',
            'proveedor' => 'required',
            'total' => 'required',
            'estado' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_presupuesto.required' => 'El campo presupuesto es obligatorio',
            'descripcion.required' => 'El campo :attribute es obligatorio',
            'numero_factura.required' => 'El campo numero factura es obligatorio',
            'serie_factura.required' => 'El campo serie factura es obligatorio',
            'proveedor.required' => 'El campo :attribute es obligatorio',
            'total.required' => 'El campo :attribute es obligatorio',
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
