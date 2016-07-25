<?php

namespace Btcc\Http\Requests;

use Btcc\Http\Requests\Request;

class ProfileUpdateRequest extends Request
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
            'first_name'=>'required|max:20',
            'last_name'=>'required|max:30',
            'phone'=>'string|max:30',
            'country_code'=>'required|string|max:4',
        ];
    }
}
