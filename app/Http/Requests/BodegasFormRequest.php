<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BodegasFormRequest extends FormRequest
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
           'numero_bodega' => [
            'required',
             Rule::unique('bodegas')->where(function ($query) {
                 $query->where([['numero_bodega', $this->numero_bodega],['id_proyecto', $this->id_proyecto],['nivel_bodega',$this->nivel_bodega]]);
             })->ignore($id)
            ],
            'medida_bodega' => 'required',
            'precio_venta' => 'required'  
        ];
    }

    public function messages()
    {
        return [
            'id_proyecto.required' => 'El campo :attribute es obligatorio',
            'numero_bodega.required' => 'El campo :attribute es obligatorio',
            'numero_bodega.unique' => 'El campo Proyecto y Apartamento ya se encuentra existentes',
            'medida_bodega.required' => 'El campo :attribute es obligatorio',
            'precio_venta.required' => 'El campo :attribute es obligatorio'
            
        ];
    }
}
