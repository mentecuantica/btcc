<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/7/2016
 * Time: 9:28 AM
 * Filename: Transactable.php
 */

namespace Btcc\Models\Transaction;

interface Transactable {
    const TYPE_REGISTER_FUNDING =1;
    const TYPE_REGISTER_WITHDRAW =2;

    const TYPE_CASHIN_FUNDING =4;
    const TYPE_CASHOUT_WITHDRAW =6;

    const TYPE_UNARY_PAYMENT =12;
    const TYPE_BINARY_PAYMENT =14;
    const TYPE_TERNARY_PAYMENT =16;

    const TYPE_MARKET_PROFITS =24;


   const STATUS_NEW = 1;
   const STATUS_PENDING = 2;
   const STATUS_IN_PROCESS = 4;
   const STATUS_SUCCESS = 6;
    const STATUS_HOLD = 8;
    const STATUS_ERROR  = 10;

}