<?php


namespace Btcc\Services;
use Illuminate\Validation\Validator;

/**
 * Class Validation
 * @package Btcc\Services
 */
class Validation extends Validator {
    /**
     * My validation rules that imply the field is required.
     *
     * @var array
     */
    protected $myImplicitRules = ['treebinaryfree'];

    /**
     * Determine if a given rule implies the attribute is required.
     *
     * @param  string  $rule
     * @return bool
     */
    protected function isImplicit($rule)
    {
        return in_array($rule, array_merge($this->implicitRules, $this->myImplicitRules));
    }

    public function validateTreebinaryfree($attribute, $value, $parameters)
    {
        return true;
    }

}