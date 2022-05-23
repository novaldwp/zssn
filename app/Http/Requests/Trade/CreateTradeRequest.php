<?php

namespace App\Http\Requests\Trade;

use Illuminate\Foundation\Http\FormRequest;

class CreateTradeRequest extends FormRequest
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
            'survivor_id'       => 'required|numeric|exists:survivors,id',
            'other_survivor'    => 'required|numeric|exists:survivors,id',
            'want'              => 'required|array|min:1',
            'want.*'            => 'required|numeric|exists:items,id',
            'wantamount'        => 'required|array|min:1',
            'wantamount.*'      => 'required|numeric|gt:0',
            'give'              => 'required|array|min:1|exists:items,id',
            'give.*'            => 'required|numeric|gt:0'
        ];
    }
}
