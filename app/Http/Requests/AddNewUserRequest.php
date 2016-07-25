<?php

namespace Btcc\Http\Requests;

use Auth;
use Btcc\Services\Subscriptions\Package;
use Btcc\Services\PackageService;
use Clockwork\Request\Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Fluent;

class AddNewUserRequest extends Request {

    /**
     * Validate the class instance.
     * @return void
     */
    public function validate()
    {
        $instance = $this->getValidatorInstance();

        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $passedBasicValidation = $instance->passes();

        if ($passedBasicValidation) {
            \Log::error('We ve passed basic validation');

            $passedBinaryValidation = TRUE;

            $instance->sometimes([
                'binary-position',
                'binary-parent-id'
            ], 'required', function (Fluent $input) use ($instance, &$passedBinaryValidation) {


                $query = \DB::connection()->query()->selectRaw('a FROM bt_validate_position_json(:pid, :pos) as a', [

                    $input->get('binary-parent-id'),
                    $input->get('binary-position')
                ]);
                $validateJsonResponse = json_decode($query->first()->a);

                foreach ($validateJsonResponse as $validationType => $isValid) {

                    $castedValue = ($isValid==="true") ? true : FALSE;

                    $passedBinaryValidation = ($passedBinaryValidation and $castedValue);
                    if ($passedBinaryValidation) {
                        continue;
                    }
                    $instance->getMessageBag()->add($validationType, sprintf('Error: %s is %d', $validationType,$isValid));

                }

                return $passedBinaryValidation;
            });

            if ($passedBinaryValidation) {
                \Log::error('Is valid binary' . $passedBinaryValidation);
                return;

            }

        }

        $this->failedValidation($instance);

    }

    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        if (\Auth::user()) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {

        return [

            'email'            => 'required|email|max:255|unique:users',
            'first_name'       => 'required',
            'last_name'        => 'required',
            'country_code'     => 'required',
            'package_id'       => 'required|min:4|max:4|is_package_exists',
            'binary-position'  => 'required|in:L,R',
            'binary-parent-id' => 'required|integer|exists:users,id',

        ];

        //return User::rules();
    }

    /**
     * @param $instance
     */
    protected function validateBinaryTreePosition($instance)
    {
      // $result = ;

      //  return $result;
    }
}
