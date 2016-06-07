<?php

namespace Btcc\Models;

use Btcc\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Btcc\Models\Wallet
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
