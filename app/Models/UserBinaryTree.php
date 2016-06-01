<?php

namespace Btcc\Models;

use Illuminate\Database\Eloquent\Model;

class UserBinaryTree extends Model
{
    protected $table = 'users_binary_tree';

    public function user()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
