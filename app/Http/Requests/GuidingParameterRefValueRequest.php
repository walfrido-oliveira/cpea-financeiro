<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuidingParameterRefValueRequest extends FormRequest
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
            'guiding_parameter_ref_value_id' => ['required', 'string', 'max:255'],
            'observation' => ['nullable', 'string', 'max:255'],
        ];
    }
}
