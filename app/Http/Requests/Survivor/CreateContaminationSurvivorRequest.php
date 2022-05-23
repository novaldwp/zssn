<?php

namespace App\Http\Requests\Survivor;

use Illuminate\Foundation\Http\FormRequest;

class CreateContaminationSurvivorRequest extends FormRequest
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
            "survivor_id" => 'required|numeric|exists:survivors,id',
            "report_by" => 'required|numeric|exists:survivors,id'
        ];
    }
}
