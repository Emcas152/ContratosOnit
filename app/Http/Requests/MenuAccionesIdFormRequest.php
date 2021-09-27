<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuAccionesIdFormRequest extends FormRequest
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
            'id.required' => 'El campo :id es obligatorio',
            'id.numeric' => 'El campo debe de ser numerico'
        ];
    }

}
