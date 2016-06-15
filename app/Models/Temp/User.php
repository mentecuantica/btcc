<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/15/2016
 * Time: 1:34 AM
 * Filename: User.php
 */

namespace Btcc\Models\Temp;

/**
 * Class User
 * @package Btcc\Models\Temp
 */
class User {

    protected $treeLinearInstance;
    protected $treeBinaryInstance;
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;

        
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }








    /**
     * @return mixed
     */
    public function getTreeBinary()
    {
        if ($this->treeBinaryInstance === null) {
            $this->treeBinaryInstance = $this->createTreeBinary();
        }

        return $this->treeBinaryInstance;
    }

    public function createTreeBinary()
    {
        return new Binary($this);
    }
    

    /**
     * @param mixed $treeBinary
     */
    public function setTreeBinary($treeBinary)
    {
        $this->treeBinary = $treeBinary;
    }

    /**
     * @return mixed
     */
    public function getTreeLinear()
    {
        return $this->treeLinear;
    }

    /**
     * @param mixed $treeLinear
     */
    public function setTreeLinear($treeLinear)
    {
        $this->treeLinear = $treeLinear;
    }



}