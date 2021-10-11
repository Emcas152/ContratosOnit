<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class NoticiaFormRequest extends FormRequest
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
            'nombre' => 'required',
            'descripcion' => 'required',
            'tipo_noticia' => 'required',
            'prioridad' => 'required',
            'fecha_publicacion' => 'required',
            'estado' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_usuario_creo.required' => 'El campo usuario es obligatorio',
            'id_usuario_creo.integer' => 'El campo usuario debe de ser un numero entero',
            'nombre.required' => 'El campo :attribute es obligatorio',
            'descripcion.required' => 'El campo :attribute es obligatorio',
            'tipo_noticia.required' => 'El campo :attribute es obligatorio',
            'prioridad.required' => 'El campo :attribute es obligatorio',
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
