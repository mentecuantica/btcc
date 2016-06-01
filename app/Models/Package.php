<?php

namespace Btcc\Models;

use Collective\Html\HtmlFacade;
use Illuminate\Database\Eloquent\Model;

/**
 * Btcc\Models\Package
 *
 * @property integer $id
 * @property string $name
 * @property integer $start_balance
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereStartBalance($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Package extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     *
     *
     * @todo Not working
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, Profile::class,'package_id','user_id');
    }



}
