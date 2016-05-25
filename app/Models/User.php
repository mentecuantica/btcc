<?php

namespace Btcc\Models;

use Btcc\Models\Profile;
use Btcc\Models\UserWallet;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Btcc\Models\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Btcc\Models\Profile $profile
 * @property-read \Btcc\Models\UserWallet $wallet
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function wallet()
    {
        return $this->hasOne(UserWallet::class);
    }


    public function invitesIssued()
    {
        return $this->hasMany(UserInvite::class);
    }


    public function transactionIssued()
    {
        return $this->hasMany(UsersTransaction::class,'user_id');
    }




}
