<?php


namespace Btcc\Services;
use Illuminate\Validation\Validator;
use LinusU\Bitcoin\AddressValidator;

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
    protected $myImplicitRules = ['treebinaryfree','enoughMoney','bitcoinAddress','hasEnoughFunds'];

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

    /**
     * run when writing data to inaccessible members.
     *
     * @param $name string
     * @param $value mixed
     *
     * @return void
     * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    function __set($name, $value)
    {
        // TODO: Implement __set() method.
    }

    public function validateEnoughMoney($attribute, $value, $messages,  $parameters)
    {



        $userFinances = user()->totalSum;

        $packageToBuy = \Subscription::find($value);

        $repreat = $packageToBuy->price - $userFinances;

        $message = sprintf('Package %s cost: %d $, need more + %d, diff',$packageToBuy->name, $packageToBuy->price, $repreat );
        $this->customMessages['enough_money']= $message;

        return ($userFinances>=$packageToBuy->price);


    }



    public function validateBitcoinAddress($attribute, $value, $messages,  $parameters)
    {
        $isValid = AddressValidator::isValid($value);

        $this->setCustomMessages(['bitcoin_address' => sprintf('Bitcoin address "%s" is invalid',$value)]);

        return $isValid;


    }

    public function validateHasEnoughFunds($attribute, $value, $messages,  $parameters)
    {


        $userFinances = user()->totalSum;

        $messages = [
            'has_enough_funds'=>sprintf('Amount to withdraw %d $ is more then you have % $',
                $value, $userFinances)
        ];

        $this->customMessages = $messages;

        return $userFinances>=$value;

    }


}