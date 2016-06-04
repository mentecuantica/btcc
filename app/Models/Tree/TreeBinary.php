<?php

namespace Btcc\Models\Tree;


use Btcc\Models\User;

class TreeBinary extends BaseTree
{
    protected $table = 'binary_tree';

    public function user()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function isTopUser()
    {
        // TODO: Implement isTopUser() method.
    }

    public function hasPartners()
    {
        // TODO: Implement hasPartners() method.
    }

    public function allPartners()
    {

        $children = \DB::select('SELECT gc.*,tc.email,tc.id as uid FROM bt_get_descendants(:id) as gc, users as tc WHERE gc.child_id = tc.id', ['id' => $this->parent_id]);
        \Debugbar::addMessage('Loaded raw:',$children);
        return $children;
    }

    public function subPartners($depthLimit = 5)
    {
        // TODO: Implement subPartners() method.
    }

    public function directPartners()
    {
        // TODO: Implement directPartners() method.
    }

}
