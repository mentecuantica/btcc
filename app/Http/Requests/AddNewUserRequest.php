<?php

namespace Btcc\Http\Requests;

use Auth;
use Btcc\Services\Subscriptions\Package;
use Btcc\Services\Subscriptions\SubscribeForPackage;

class AddNewUserRequest extends Request
{


    /**
     * Validate the class instance.
     *
     * @return void
     */
    public function validate()
    {
        $instance = $this->getValidatorInstance();

        if(! $this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        elseif (! $instance->passes()) {
            $this->failedValidation($instance);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (\Auth::user()) {
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
                    'country_code' => 'required',
                    'package_id' => 'required|min:4|max:4|is_package_exists',
                    'binary-position'=>'required|in:L,R',
                    'binary-parent-id'=>'required|integer|exists:users,id',


            ];

        //return User::rules();
    }
}
