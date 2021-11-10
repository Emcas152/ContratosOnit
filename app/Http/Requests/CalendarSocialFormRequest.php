<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CalendarSocialFormRequest extends FormRequest
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
            'calendar' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'comment' => 'required|string',
            'id_usuario' => 'required|integer',
            'start' => 'required',
            'end' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'calendar.required' => 'El campo area es obligatorio',
            'calendar.integer' => 'El campo area debe de ser un numero entero',
            'title.required' => 'El campo titulo es obligatorio',
            'title.string' => 'El titulo debe de ser un texto',
            'description.required' => 'El campo descripcion es obligatorio',
            'description.string' => 'El descripcion debe de ser un texto',
            'comment.required' => 'El campo comentarios es obligatorio',
            'comment.string' => 'El comentarios debe de ser un texto',
            'id_usuario.required' => 'El campo usuario es obligatorio',
            'id_usuario.integer' => 'El campo usuario debe de ser un numero entero',
            'start.required' => 'El campo fecha inicio es obligatorio',
            'end.required' => 'El campo fecha finaliza es obligatorio',
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
