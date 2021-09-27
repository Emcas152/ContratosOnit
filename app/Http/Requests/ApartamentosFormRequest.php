<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApartamentosFormRequest extends FormRequest
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
           'id_proyecto' => 'required' ,
           'numero_apartamento' => [
            'required',
             Rule::unique('apartamentos')->where(function ($query) {
                 $query->where([['numero_apartamento', $this->numero_apartamento],['id_proyecto', $this->id_proyecto],['torre_apartamento', $this->torre_apartamento]]);
             })->ignore($id)
            ],
            'medida_interior' => 'required',
            'precio_m_interior' => 'required',
            'precio_venta' => 'required'
            
        ];
    }

    public function messages()
    {
        return [
            'id_proyecto.required' => 'El campo :attribute es obligatorio',
            'numero_apartamento.required' => 'El campo :attribute es obligatorio',
            'numero_apartamento.unique' => 'El campo Proyecto y Apartamento ya se encuentra existentes',
            'precio_m_interior.required' => 'El campo :attribute es obligatorio',
            'precio_venta.required' => 'El campo :attribute es obligatorio'
            
        ];
    }
}
