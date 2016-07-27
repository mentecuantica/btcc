<?php

namespace Btcc\Models\Tree;


use Btcc\Models\User;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
class TreeTernary extends BaseTree
{

    //use Singleton

    protected $table = 'tree_ternary';


    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var string
     */
    protected $userKey;


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

        return (count($result)>0);

    }


    /**
     * @return array
     */
    public function getPartners()
    {

      return static::getDescendants($this->userId);
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
        $descentants = DB::select('SELECT count(*) FROM ternary_get_descendants(:id,:level)',['id'=>$this->userId,'level'=>100]);
        return $descentants[0]->count;
    }







    /**
     *
     * Creates query for children
     *
     * @param int $depth
     *
     * @return $this
     */
    public function queryChildren(int $depth = 5)
    {
        $this->userKey = 'user_id';
        $this->query = static::descendants($this->userId, $depth);
        return $this;

    }

    /**
     * Create query for parents
     *
     * @return $this
     */
    public function queryParents()
    {
        $this->userKey = 't.id';
        $this->query = static::ancestors($this->userId);
        return $this;
    }

    /**
     * Adds query with users
     *
     * @param array $userFields
     *
     * @return $this
     */
    public function joinUsers(array $userFields = ['u.id','name','email'])
    {
        $this->query = $this->query
            ->join('users as u','u.id','=',$this->userKey)
            ->addSelect($userFields);

        return $this;
    }

    /**
     * @return array|static[]
     */
    public function getAsArray() {
        return $this->query->get();
    }

    /**
     * @param $modelName
     *
     * @return Collection
     */
    public function getAsModels($modelName = User::class)
    {
        return $modelName::hydrate($this->getAsArray());

    }


    /**
     *
     * @todo Make it a query builder ?
     *
     * @return array
     */
    private static function getAncestors(int $id)
    {
        $ancestors = DB::select('SELECT id FROM public.ternary_get_ancestors(:id)',['id'=>$id]);
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
        $descentants = DB::select('SELECT parent_id,user_id,  t_position, depth, level_rel,path_to_root 
          FROM ter ternary_get_descendants(:id,:level)',['id'=>$userId,'level'=>100]);
        return $descentants;
    }

    /**
     * @param int $parentId
     * @param int $depth
     *
     * @return Builder
     */
    public static function descendants(int $parentId,int $depth = 5) {

        $fnc = sprintf('ternary_get_descendants(%d,%d) as t',$parentId,$depth);
        return DB::query()->addSelect(['t.user_id','t.parent_id','t.depth','t.t_position','t.level_rel','t.path_to_root'])->from(DB::raw($fnc));
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public static function ancestors($parentId) {

        $fnc = sprintf('ternary_get_ancestors(%d) as t',$parentId);
        return DB::query()->addSelect(['t.id'])->from(DB::raw($fnc));
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
        // TODO: Implement countFirstPartners() method.
    }

    public function getFirstPartners()
    {
        // TODO: Implement countFirstPartners() method.
    }

    /**
     * @return User
     */
    public function getParentUser()
    {
        // TODO: Implement getParentUser() method.
    }

}
