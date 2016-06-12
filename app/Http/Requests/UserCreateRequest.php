<?php

namespace Btcc\Http\Requests;

use Auth;
use Btcc\Http\Requests\Request;
use Btcc\Models\User;
use Btcc\Repositories\Users\Transaction\Transaction;

class UserCreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (\Sentinel::getUser()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                    'email'           => 'required|email|max:255|unique:users',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'country_id' => 'required|integer',
                    'package_id' => 'required|integer|exists:packages,id',
                    'binary-position'=>'required|in:L,R',
                    'binary-parent-id'=>'required|integer|exists:users,id',

            ];

        //return User::rules();
    }
}
