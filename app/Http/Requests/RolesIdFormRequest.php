<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolesIdFormRequest extends FormRequest
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
            'name' => 'required|max:191|unique:roles,name,'.$id.',id'    
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo :attribute es obligatorio',
            'name.unique' => 'El campo :attribute ya se encuentra existente',
            'name.max' => 'El campo :attribute no debe de exceder los 191 caracteres'
        ];
    }
}
