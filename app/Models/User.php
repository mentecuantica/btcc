<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/1/2016
 * Time: 3:35 PM
 * Filename: User.php
 */

namespace Btcc\Models;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;
use Illuminate\Contracts\Auth\Authenticatable;



/**
 * Class User
 * @package Btcc\Models
 */
class User extends SentinelUser  implements Authenticatable {

    protected $fillable = [
        'email',
        'username',
        'password',
        'last_name',
        'first_name',
        'permissions',
    ];

    protected $loginNames = ['email','username'];


    public function getAuthIdentifierName()
    {
        return $this->getUserLoginName();
    }



    public function getAuthIdentifier()
    {
      return $this->getAuthIdentifier();

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