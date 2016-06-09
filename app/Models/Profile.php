<?php

namespace Btcc\Models;

use Btcc\Models\User;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

/**
 * Btcc\Models\Profile
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $surname
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Btcc\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Profile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Profile whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Profile whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Profile whereSurname($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Profile whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Btcc\Models\Package $package
 */
class Profile extends Model
{
    use ValidatingTrait;
    
    protected $fillable = ['name','surname','package_id','phone'];

    /**
     * New inline validation from
     * use Watson\Validating\ValidatingTrait;
     * 
     * @var array
     */
    protected $rules = [
        //'name'   => 'required',
        'country'   => 'required',
        //'surname'    => 'required|unique:posts,slug',
        'package_id' => 'required|exists:packages,id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->hasOne(Package::class);
    }
}
