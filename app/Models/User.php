<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/1/2016
 * Time: 3:35 PM
 * Filename: User.php
 */

namespace Btcc\Models;
use Btcc\Models\Invite\Invite;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\Tree\TreeLinear;
use Btcc\Models\Wallet\Wallet;
use Btcc\Services\BinaryTree;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class User
 * @package Btcc\Models
 */
class User extends SentinelUser  implements Authenticatable {


    public function binary()
    {
        return $this->hasMany(TreeBinary::class,'parent_id','id');
    }

    public function linear()
    {

        return $this->hasMany(TreeLinear::class,'parent_id','id');
    }

    public function binaryTree()
    {

        return $this->hasManyThrough(User::class,TreeBinary::class,'child_id');
    }


    protected $fillable = [
        'email',
        'username',
        'password',
        'last_name',
        'first_name',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden
        = [
            'password',
        ];

    protected $loginNames = ['email','username'];





    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function wallet()
    {

        return $this->hasOne(Wallet::class);
    }

    public function invitesIssued()
    {
        return $this->hasMany(Invite::class);
    }

    public function transactionsSent()
    {
        return $this->hasMany(Transaction::class, 'sender');
    }

    public function transactionsRecieved()
    {
        return $this->hasMany(Transaction::class, 'reciever');
    }






    public function getAuthIdentifier()
    {
        $this->getAuthIdentifier();
    }



    public function getAuthIdentifierName()
    {
        return $this->getUserLoginName();
    }



    public function getAuthPassword()
    {
        // TODO: Implement getAuthPassword() method.
    }

    public function getRememberToken()
    {
        return "xxxxxx";
    }

    public function setRememberToken($value)
    {
        $v = $value;
    }

    public function getRememberTokenName()
    {
        return "9888";
    }

}