<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/7/2016
 * Time: 6:39 AM
 * Filename: TreeRepository.php
 */

namespace Btcc\Repositories;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

/**
 * Class TreeRepository
 * @package Btcc\Repositories
 */
class TreeRepository {

    /**
     * @var Builder
     */
    protected $query;
    protected $userKey;


    /**
     * @param int $parentId
     *
     * @example $repository->binaryChildren(1,10)->joinUsers()->get();
     *
     * @return TreeRepository
     */
    public function binaryChildren(int $parentId, int $depth = 5)
    {
        $this->userKey = 'child_id';
       $this->query = TreeBinary::descendants($parentId, $depth);
        return $this;

    }




    public function binaryParents(int $userId)
    {
        $this->userKey = 'ancestor';
        $this->query = TreeBinary::ancestors($userId);
        return $this;

    }


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
    public function get() {
        return $this->query->get();
    }

    /**
     * @param $modelName
     *
     * @return Collection
     */
    public function asModels($modelName = User::class)
    {
      return $modelName::hydrate($this->get());

    }

}