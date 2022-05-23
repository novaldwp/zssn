<?php

namespace App\Http\Requests\Survivor;

use Illuminate\Foundation\Http\FormRequest;

class CreateSurvivorRequest extends FormRequest
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
            'name'      => 'required|string',
            'age'       => 'required|numeric|gt:0', // age must be greater than 0
            'latitude'  => 'required',
            'longitude' => 'required',
            'gender_id' => 'required|exists:genders,id', // gender_id value must be exist in table gender column id
            'items'     => 'required|array|min:1',
            'items.*'   => 'required|exists:items,id',
            'amounts'   => 'required|array|min:1',
            'amounts.*' => 'required|numeric|gt:0'
        ];
    }
}
