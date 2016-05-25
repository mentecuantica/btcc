<?php

namespace Btcc\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Btcc\Models\UserInvite
 * @property integer        $id
 * @property integer        $user_id
 * @property integer        $package_id
 * @property string         $email
 * @property integer        $type
 * @property integer        $status
 * @property integer        $sender
 * @property string         $comment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite wherePackageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite whereSender($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\UserInvite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserInvite extends Model {
    const NEW = 1;
    const SENT = 2;
    const RESPONDED = 3;
    const FINISHED = 4;

    public static $statuses
        = [
            1 => 'NEW',
            2 => 'SENT',
            3 => 'RESPONDED',
            4 => 'FINISHED'
        ];

    protected $fillable = ['email'];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->hasOne(Package::class);
    }
}
