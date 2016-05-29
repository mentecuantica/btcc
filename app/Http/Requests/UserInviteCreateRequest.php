<?php

namespace Btcc\Http\Requests;

use Btcc\Http\Requests\Request;

class UserInviteCreateRequest extends Request
{
    /**
     * The URI to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirect;

    /**
     * The route to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute;

    /**
     * The controller action to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirectAction;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $idFromRoute = $this->route()->parameter('id');

        if (\Auth::id() == $idFromRoute) {
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
            'email'=>'required|email',
          //  'user_id'=>'required',
            'package_id'=>'required',
            'type'=>'required',
        ];
    }
}
