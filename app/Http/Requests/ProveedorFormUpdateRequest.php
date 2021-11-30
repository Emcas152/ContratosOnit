<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ProveedorFormUpdateRequest extends FormRequest
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
        $id = $this->get('id_usuario');

        return [
            'name' => 'required|max:191|unique:users,name,'.$id.',id',
            'email' => 'required|max:191|unique:users,email,'.$id.',id',
            'password' => 'required|max:191',
            'descripcion' => 'required',
            'nombre' => 'required',
            'direccion' => 'required',
            'informacion_general' => 'required',
            'pagina_web' => 'required',
            'estado' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo :attribute es obligatorio',
            'name.unique' => 'El campo :attribute ya se encuentra existente',
            'name.max' => 'El campo :attribute no debe de exceder los 191 caracteres',
            'email.required' => 'El campo :attribute es obligatorio',
            'email.unique' => 'El campo :attribute ya se encuentra existente',
            'email.max' => 'El campo :attribute no debe de exceder los 191 caracteres',
            'password.required' => 'El campo :attribute es obligatorio',
            'descripcion.required' => 'El campo :attribute esta obligatorio',
            'nombre.required' => 'El campo :attribute esta obligatorio',
            'direccion.required' => 'El campo :attribute esta obligatorio',
            'informacion_general.required' => 'El campo :attribute esta obligatorio',
            'pagina_web.required' => 'El campo Pagina Web esta obligatorio',
            'estado.required' => 'El campo :attribute esta obligatorio',
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
