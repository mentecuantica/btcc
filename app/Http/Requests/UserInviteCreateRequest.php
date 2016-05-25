<?php

namespace Btcc\Http\Requests;

use Btcc\Http\Requests\Request;

class UserInviteCreateRequest extends Request
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
            'email'=>'required|email',
          //  'user_id'=>'required',
            'package_id'=>'required',
            'type'=>'required',
        ];
    }
}
