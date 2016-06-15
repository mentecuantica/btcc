<?php

namespace Btcc\Models\Tree;


use Btcc\Models\User;
use Btcc\Traits\BinaryTreeTrait;
use Btcc\Traits\Singleton;
use DB;

class TreeBinary extends BaseTree
{
    use BinaryTreeTrait;

    //use Singleton

    protected $table = 'binary_tree';

    /**
     * @param $parentId
     * @param $position
     * @param $userId
     *
     * @return array
     */
    public static function addToParent($parentId, $position, $userId)
    {
        $result = DB::select('SELECT bt_add_to_parent(:parentId,:position, :userId)',[
            'parentId'=>$parentId,
            'position'=>$position,
            'userId'=>$userId
        ]);
        return $result;
    }

    /**
     * @return bool
     */
    public function isTopUser()
    {
        $result = static::getAncestors($this->userId);

        if (count($result)==0) {
            return true;
        }

        return FALSE;
    }

    /**
     * @return bool
     */
    public function hasPartners()
    {
        $result = static::getDescendants($this->userId);

        if (count($result)==0) {
            return true;
        }

        return FALSE;
    }

    /**
     *
     * @todo Make it a query builder ?
     *
     * @return array
     */
    private static function getAncestors($id)
    {
        $ancestors = DB::select('SELECT ancestor as user_id, p FROM bt_get_ancestors(:id)',['id'=>$id]);
        return $ancestors;
    }

    /**
     *
     * @todo Make it a query builder ?
     *
     * @param     $userId
     * @param int $level
     *
     * @return array
     */
    private static function getDescendants($userId, $level = 100)
    {
        $descentants = DB::select('SELECT * FROM bt_get_descendants(:id,:level)',['id'=>$userId,'level'=>100]);
        return $descentants;
    }

    /**
     * @return array
     */
    public function getPartners()
    {

        $children = \DB::select('SELECT gc.*,tc.email,tc.id as uid FROM bt_get_descendants(:id) as gc, users as tc WHERE gc.child_id = tc.id', ['id' => $this->userId]);
        \Debugbar::addMessage('Loaded raw:',$children);
        return $children;
    }

    public function getPartnersLimit($depthLimit = 5)
    {

    }

    public function getFirstPartners()
    {
        // TODO: Implement directPartners() method.
    }

    /**
     * @return User
     */
    public function getParentUser()
    {
        // TODO: Implement getParentUser() method.
    }

    public function countPartners()
    {
        $descentants = DB::select('SELECT count(*) FROM bt_get_descendants(:id,:level)',['id'=>$this->userId,'level'=>100]);
        return $descentants[0]->count;
    }

    public function countFirstPartners()
    {
        // TODO: Implement countFirstPartners() method.
    }

    public function getParents()
    {
        return static::getAncestors($this->userId);

    }

}
