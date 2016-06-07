<?php

namespace Btcc\Models;

use Btcc\Models\Profile;
use Btcc\Models\Wallet;

use Btcc\Traits\LinearTreeable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Btcc\Models\User
 *
 * @property integer                   $id
 * @property string                    $name
 * @property string                    $email
 * @property string                    $password
 * @property string                    $remember_token
 * @property \Carbon\Carbon            $created_at
 * @property \Carbon\Carbon            $updated_at
 * @property-read \Btcc\Models\Profile $profile
 * @property-read \Btcc\Models\Wallet  $wallet
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Btcc\Models\UserMLM $parent
 * @property-read \Baum\Extensions\Eloquent\Collection|\Btcc\Models\UserMLM[] $children
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class UserMLM extends \Baum\Node implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {
    use Authenticatable, Authorizable, CanResetPassword;

   

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable
        = [
            'name',
            'email',
            'password',
            'binary_position'
        ];

 

  

  



}
