<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/15/2016
 * Time: 2:56 AM
 * Filename: UserWithTrees.php
 */

namespace Btcc\Traits;

use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\Tree\TreeLinear;
use Btcc\Models\Tree\TreeTernary;
use Btcc\Models\Tree\UserTreeable;

trait UserWithTrees {




    /**
     * @var TreeLinear
     */
    protected $treeLinear;

    /**
     * @var TreeBinary
     */
    protected $treeBinary;


    /**
     * @var TreeBinary
     */
    protected $treeTernary;



    /**
     * @return UserTreeable
     */
    public function getTreeBinary()
    {
        if ($this->treeBinary === null) {
            $this->treeBinary = $this->createTreeBinary();
        }

        return $this->treeBinary;

    }


    /**
     * @return UserTreeable
     */
    public function getTreeTernary()
    {
        if ($this->treeTernary === null) {
            $this->treeTernary = $this->createTreeTernary();
        }

        return $this->treeTernary;

    }

    /**
     * @return UserTreeable
     */
    public function getTreeLinear()
    {
        if ($this->treeLinear === null) {
            $this->treeLinear = $this->createTreeLinear();
        }

        return $this->treeLinear;
    }

    /**
     * @return \Btcc\Models\Tree\TreeBinary
     */
    public function createTreeBinary()
    {
        return new TreeBinary($this);
    }


    /**
     * @return \Btcc\Models\Tree\TreeTernary
     */
    public function createTreeTernary()
    {
        return new TreeTernary($this);
    }

    /**
     * @return \Btcc\Models\Tree\TreeLinear
     * @throws \Exception
     */
    public function createTreeLinear()
    {
        $userId = $this->id;
        $treeLinear = TreeLinear::where(['user_id'=>$userId])->first();

        if ($treeLinear == FALSE) {
            throw new \Exception('No related linear model with user id: '.$userId);
        }

        return new $treeLinear;
    }



    protected static $linearTree;

    /**
     * @return mixed
     */
    public function getLinearTreeStatic()
    {
        if (static::$linearTree === NULL) {
            static::$linearTree = TreeLinear::getInstance();
        }

        return static::$linearTree;
    }


}