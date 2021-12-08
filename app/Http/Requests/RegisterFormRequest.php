<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterFormRequest extends FormRequest
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
            'name' => 'required|max:191|unique:users',
            'email' => 'email|required|unique:users',
            'password' => 'required|max:191|confirmed',
            'id_condominio' => 'required',
            'id_apartamento' => 'required',
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
            'password.confirmed' => 'El campo :attribute no esta confirmado',
            'id_condominio.required' => 'El campo Condominio esta obligatorio',
            'id_apartamento.required' => 'El campo Apartamento esta obligatorio',
            'estado.required' => 'El campo :attribute es obligatorio',
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
