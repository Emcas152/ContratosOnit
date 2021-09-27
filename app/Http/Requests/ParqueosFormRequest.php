<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParqueosFormRequest extends FormRequest
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
            'numero_parqueo' => 'required|unique:parqueos,numero_parqueo,'.$id.',id',
            'id_proyecto' => 'required'  
        ];
    }

    public function messages()
    {
        return [
            'numero_parqueo.required' => 'El campo :attribute es obligatorio',
            'numero_parqueo.unique' => 'El campo :attribute ya se encuentra existente',
            'id_proyecto.required' => 'El campo :attribute es obligatorio'
        ];
    }
}
