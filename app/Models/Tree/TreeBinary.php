<?php

namespace Btcc\Models\Tree;


use Btcc\Models\User;
use Btcc\Traits\BinaryTreeTrait;
use DB;

class TreeBinary extends BaseTree
{
    use BinaryTreeTrait;
    
    protected $table = 'binary_tree';

    public static function addToParent($parentId, $position, $userId)
    {
        $result = DB::select('SELECT bt_add_to_parent(:parentId,:position, :userId)',[
            'parentId'=>$parentId,
            'position'=>$position,
            'userId'=>$userId
        ]);
        return $result;
    }


    public function getParentUser()
    {
        // TODO: Implement getParentUser() method.
    }

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

    /**
     *
     * @return array
     */
    public function getAncestors()
    {
        $ancestors = DB::select('SELECT ancestor as user_id, p FROM bt_get_ancestors(:id)',['id'=>$this->user()->id]);
        return $ancestors;
    }

    public function getDescendants()
    {
        $descentants = DB::select('SELECT * FROM bt_get_descendants(:id)',['id'=>$this->user()->id]);
        return $descentants;
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
