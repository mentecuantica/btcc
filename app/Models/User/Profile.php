<?php

namespace Btcc\Models;

use Illuminate\Database\Eloquent\Model;

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
 */
class Profile extends Model
{
    
    protected $fillable = ['name','surname'];
    
    
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
