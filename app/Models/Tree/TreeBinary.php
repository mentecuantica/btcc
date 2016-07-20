<?php

namespace Btcc\Models\Tree;


use Btcc\Models\User;
use DB;
use Illuminate\Database\Query\Builder;

class TreeBinary extends BaseTree
{

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
        $ancestors = DB::select('SELECT ancestor, bt_position FROM public.bt_get_ancestors(:id)',['id'=>$id]);
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
        $descentants = DB::select('SELECT user_id, parent_id, bt_position, depth, level 
          FROM bt_get_descendants_with_parent(:id,:level)',['id'=>$userId,'level'=>100]);
        return $descentants;
    }

    /**
     * @param int $parentId
     * @param int $depth
     *
     * @return Builder
     */
    public static function descendants(int $parentId,$depth = 5) {

        $fnc = sprintf('bt_get_descendants_with_parent(%d,%d) as t',$parentId,$depth);
        return DB::query()->addSelect(['t.user_id','t.parent_id','t.level','t.bt_position','t.level'])->from(DB::raw($fnc));
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public static function ancestors($parentId) {

        $fnc = sprintf('bt_get_ancestors(%d) as t',$parentId);
        return DB::query()->addSelect(['t.ancestor as user_id','t.bt_position as pos'])->from(DB::raw($fnc));
    }


    /**
     * @return array
     */
    public function getPartners()
    {

        $children = \DB::select('SELECT gc.*,tc.email,tc.id as uid FROM bt_get_descendants(:id) as gc, users as tc WHERE gc.user_id = tc.user_id', ['id' => $this->userId]);
        \Debugbar::addMessage('Loaded raw:',$children);
        return $children;
    }

    /**
     * bt_get_descendants returns
     *
     * id, parent_id, user_id, bt_position, depth, level
     *
     * @return mixed
     */

    public function countPartners()
    {
        $descentants = DB::select('SELECT count(*) FROM bt_get_descendants(:id,:level)',['id'=>$this->userId,'level'=>100]);
        return $descentants[0]->count;
    }



    public function getParents()
    {
        return static::getAncestors($this->userId);

    }

    public function countFirstPartners()
    {
        // TODO: Implement countFirstPartners() method.
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


    /**
     * @param $userId
     *
     * @return array
     */
    public static function generateJson($rows, $parentId)
    {
        //  $rows = static::getUserTree($userId);


        if (count($rows) == 0) {
            // no children

            $parent = new \stdClass();
            $parent->user_id = $parentId;
            $parent->name = user()->email;
            $jsonNodes = json_encode([]);

            return [
                $parent,
                $jsonNodes
            ];
        }

        /**
         * If remove first element from rows, to make it parent
         *      then buildRigidArrayTreeStructureForTreant fails

         */
        $revesedArray = array_reverse($rows, TRUE);
        $parent = array_pop($revesedArray);
        //unset($rows[0]);

        $items = (new TreeDecorator([]))::stdClassToArray($rows);

        $jsonNodes = json_encode(static::buildRigidArrayTreeStructureForTreant($items, $parentId));



        return [
            $parent,
            $jsonNodes
        ];
    }

    /**
     * @param $elements
     * @param int   $parentId
     *
     * @return array
     */
    public static function buildRigidArrayTreeStructureForTreant($elements, $parentId = 1)
    {

        $branch = [];

        foreach ($elements as $n) {
            $stringPosition = $n['bt_position'];

            $n['text'] = [
                'title' => $stringPosition,
                'desc' =>'ID:' . $n['user_id'] . ' LEV:' . $n['level'], //$n['name'] . ,

            ];
            $n['link'] = ['href' => url('/tree/binary/show', $n['user_id'])];
            $n['HTMLclass'] = 'partner partner-'.$stringPosition;

            if ($n['parent_id'] == $parentId) {

                $children = static::buildRigidArrayTreeStructureForTreant($elements, $n['user_id']);
                if ($children) {
                    $n['children'] = $children;
                }
                $branch[] = $n;
            }
        }

        // \Debugbar::addMessage('Final ',$branch);

        return $branch;
    }


}
