<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParametrosDetalleIdFormRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge(['id'=> $this->route('id')]);
    }

    public function rules()
    {
        return [
            'id' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El campo :attribute es obligatorio',
            'id.numeric' => 'El campo :attribute debe de ser numerico'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    
}
