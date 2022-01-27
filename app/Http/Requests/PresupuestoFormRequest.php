<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PresupuestoFormRequest extends FormRequest
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
            'fecha_inicio' => 'required',
            'fecha_vencimiento' => 'required',
            'id_condominio' => 'required|integer',
            'descripcion' => 'required',
            'presupuesto' => 'required',
            'usuario_creo' => 'required|integer',
            'estado' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'fecha_inicio.required' => 'El campo :attribute es obligatorio',
            'fecha_vencimiento.required' => 'El campo :attribute es obligatorio',
            'id_condominio.required' => 'El campo condominio creo es obligatorio',
            'id_condominio.integer' => 'El campo condominio creo debe de ser un numero entero',
            'descripcion.required' => 'El campo :attribute es obligatorio',
            'presupuesto.required' => 'El campo :attribute es obligatorio',
            'usuario_creo.required' => 'El campo usuario creo es obligatorio',
            'usuario_creo.integer' => 'El campo usuario creo debe de ser un numero entero',
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
            'message' => 'solicitud incorrecta',
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 400));
    }
}
