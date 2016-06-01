<?php

namespace Btcc\Models\Wallet;

use Btcc\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Btcc\Models\UserWallet
 *
 * @property-read \Btcc\Models\User $user
 * @mixin \Eloquent
 */
class Wallet extends Model
{
    /**
     *
     * @return \Btcc\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}