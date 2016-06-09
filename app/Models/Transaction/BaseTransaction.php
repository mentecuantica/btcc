<?php

namespace Btcc\Models\Transaction;

use Btcc\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Btcc\Models\UsersTransaction
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $amount
 * @property integer $type
 * @property integer $status
 * @property integer $sender
 * @property integer $reciever
 * @property boolean $debit_flag
 * @property string $comment
 * @property string $hash
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereSender($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereReciever($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereDebitFlag($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Btcc\Models\User $parentUser
 * @property-read \Btcc\Models\User $recieverUser
 * @property-read \Btcc\Models\User $senderUser
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction ofType($type)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Transaction\BaseTransaction processed()
 */
class BaseTransaction extends Model
{
    const TYPE_REGISTER_FUNDING =1;
    const TYPE_REGISTER_WITHDRAW =2;
    const TYPE_CASHIN_FUNDING =4;
    const TYPE_CASHOUT_WITHDRAW =6;
    const TYPE_UNARY_PAYMENT =12;
    const TYPE_BINARY_PAYMENT =14;
    const TYPE_TERNARY_PAYMENT =16;
    const TYPE_STOCK_PROFITS =24;


    public function getPossibleTypes() {
        return array_keys(static::getTransactionTypesValues());


    }

    public static function getTransactionTypesValues()
    {
        return [
            static::TYPE_REGISTER_FUNDING=>'TYPE_REGISTER_FUNDING',
            static::TYPE_REGISTER_WITHDRAW=>'TYPE_REGISTER_WITHDRAW',
            static::TYPE_CASHIN_FUNDING=>'TYPE_CASHIN_FUNDING',
            static::TYPE_CASHOUT_WITHDRAW=>'TYPE_CASHOUT_WITHDRAW',

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

    protected $original = [
      'status'=>0,
    ];

    protected $fillable = [
        'type',
        'amount',
        'reciever',
        'comment',
    ];

    /**
     * @return User
     */
    public function parentUser()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return User
     */
    public function recieverUser()
    {
        return $this->belongsTo(User::class,'reciever');
    }

    /**
     * @return User
     */
    public function senderUser()
    {
        return $this->belongsTo(User::class,'sender');
    }


    /**
     * Scope a query to only include Transaction of a given type.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeProcessed($query)
    {
        return $query;
    }

    protected function asJson($value)
    {
        return parent::asJson($value); // TODO: Change the autogenerated stub
    }

}
