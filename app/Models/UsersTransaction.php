<?php

namespace Btcc\Models;

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
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereSender($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereReciever($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereDebitFlag($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UsersTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UsersTransaction extends Model
{
    
}
