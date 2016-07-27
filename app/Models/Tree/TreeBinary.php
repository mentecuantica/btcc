<?php

namespace Btcc\Models\Tree;


use DB;
use Btcc\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
class TreeBinary extends BaseTree
{

    //use Singleton

    protected $table = 'binary_tree';


    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var string
     */
    protected $userKey;

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
        $descentants = DB::select('SELECT count(*) FROM bt_get_descendants(:id,:level)',['id'=>$this->userId,'level'=>100]);
        return $descentants[0]->count;
    }

    public static function extractParentRowJson($rows,$parentId)
    {
        if (count($rows)>0) {
            $revesedArray = array_reverse($rows, TRUE);
            return array_pop($revesedArray);
        }
            // no children

            $parent = new \stdClass();
            $parent->user_id = $parentId;
            $parent->name = user()->email;
            return $parent;
    }


    /**
     * @param $userId
     *
     * @return array
     */
    public static function generateJson($rows, $parentId)
    {

        if (count($rows) == 0) {
            return json_encode([]);
        }

        /**
         * If remove first element from rows, to make it parent
         *      then buildRigidArrayTreeStructureForTreant fails

         */
        //$revesedArray = array_reverse($rows, TRUE);
        //$parent = array_pop($revesedArray);
        //unset($rows[0]);

        //$items = (new TreeDecorator([]))::stdClassToArray($rows);
        $items = TreeDecorator::stdClassToArray($rows);


        return json_encode(static::buildRigidArrayTreeStructureForTreant($items, $parentId));

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
            //$n['link'] = ['href' => url('/tree/binary/show', $n['user_id'])];
            $n['HTMLclass'] = 'partner partner-'.$stringPosition;

            if ($n['parent_id'] == $parentId) {

                $children = static::buildRigidArrayTreeStructureForTreant($elements, $n['user_id']);
                if ($children) {
                    $n['children'] = $children;
                }
                $branch[] = $n;
            }
        }


        return $branch;
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
        $this->userKey = 'ancestor';
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
    public static function descendants(int $parentId,int $depth = 5) {

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
