<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyectosFormRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'codigo' => 'required|max:41|unique:proyectos,codigo,'.$id.',id' ,
            'descripcion' => 'required|max:120'  
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'El campo :attribute es obligatorio',
            'codigo.unique' => 'El campo :attribute ya se encuentra existente',
            'codigo.max' => 'El campo :attribute no debe de exceder los 41 caracteres',
            'descripcion.required' => 'El campo :attribute es obligatorio',
            'descripcion.max' => 'El campo :attribute no debe de exceder los 120 caracteres'
        ];
    }
}
