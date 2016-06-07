<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/29/2016
 * Time: 10:52 AM
 * Filename: Transaction.php
 */

namespace Btcc\Models;

use Btcc\Models\Transaction\BaseTransaction;

/**
 * Class Transaction
 *
 * @package app\Models
 * Btcc\Models\Transaction
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property integer $type
 * @property integer $status
 * @property integer $sender
 * @property integer $reciever
 * @property boolean $debit_flag
 * @property string  $comment
 * @property string  $hash
 * @property boolean $credit_flag
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Btcc\Models\User $recieverUser
 * @property-read \Btcc\Models\User $senderUser
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereSender($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereReciever($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereDebitFlag($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereCreditFlag($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction ofType($type)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction processed()
 * @mixin \Eloquent
 * @property-read \Btcc\Models\User $parentUser
 */
class Transaction extends BaseTransaction {

    protected $table = 'users_transactions';

  

    public static function totalAmount()
    {
        return static::sum('amount'); // TODO: Change the autogenerated stub
    }

}