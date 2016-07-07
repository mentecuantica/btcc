<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/7/2016
 * Time: 9:29 AM
 * Filename: TypeTrait.php
 */

namespace Btcc\Models\Transaction;

/**
 * Class TypeTrait
 * @package Btcc\Models\Transaction
 */
trait TypeTrait  {

    public function getPossibleTypes() {
        return array_keys(static::getTransactionTypesValues());


    }

    public static function getTransactionTypesValues()
    {
        return [
            static::TYPE_REGISTER_FUNDING=>'REGISTER_FUNDING',
            static::TYPE_REGISTER_WITHDRAW=>'REGISTER_WITHDRAW',
            static::TYPE_CASHIN_FUNDING=>'CASHIN_FUNDING',
            static::TYPE_CASHOUT_WITHDRAW=>'CASHOUT_WITHDRAW',

        ];
    }



    /**
     * @return Collection
     */
    public static function getTransactionTypes() {
        $transactionTypes = collect([
            static::TYPE_BINARY_PAYMENT=>[
                'n'=>'BINARY+',
                'role'=>'parent',
                'amount'=>'100'
            ],
            static::TYPE_TERNARY_PAYMENT=>[
                'n'=>'TERNARY+',
                'role'=>'parent',
                'amount'=>'100'
            ],
            static::TYPE_REGISTER_FUNDING=>[
                'n'=>'REG+',
                'role'=>'parent',
                'scenario'=>'package',
                'amount'=>'100'
            ],
            static::TYPE_REGISTER_WITHDRAW=>[
                'n'=>'REG-',
                'role'=>'parent',
                'scenario'=>'package',
                'amount'=>'100'
            ],

        ]);

        return $transactionTypes;

        /*   return $transactionTypes->groupBy('n',true)->map(function ($item,$key) {
               return [$key, $item];
           });*/

    }
}