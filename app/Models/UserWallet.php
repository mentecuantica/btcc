<?php

namespace Btcc\Models;

use Btcc\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    /**
     *
     * @return \Btcc\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
