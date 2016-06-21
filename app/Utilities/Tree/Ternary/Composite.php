<?php

namespace Btcc\Utilities\Tree\Ternary;
use Illuminate\Support\Collection;

/**
 * Class CompsiteNode
 * @package Btcc\Utilities\TernaryTree
 */
abstract class Composite  {
    /**
     * @var Collection
     */
    protected $nodes = [];


    protected $l = NULL;
    protected $m = NULL;
    protected $r = NULL;
    //protected $m = self::NO_NODE;
    //protected $r = self::NO_NODE;

    function getComposite() {
        return $this;
    }

    public function getCount() {
        return count($this->nodes);
    }

    public function getNodes() {
        return $this->nodes;
    }

    public function hasFreePositions()
    {
        return count($this->getNodes())<3;


    }

    public function hasNodes() {

            return (count($this->nodes)>0);

    }

   
    /**
     * @param \Btcc\Utilities\TernaryTree\NodeAdv $newNode
     *
     * @return Composite
     */
    public function addNode(Node $newNode)
    {

        if (count($this->getNodes())==3) {
            return false;
        }

        $free = 0;
        $this->nodes[] = $newNode;
        Node::incByLevel($newNode->level);
        if ($this->getL()===Null) {
            $this->setL($newNode);
            return $this;
        }
        if ($this->getM()===Null) {
                    $this->setM($newNode);
            return $this;
        }
        if ($this->getR()===Null) {
                    $this->setR($newNode);
            return $this;
        }

       return true;
    }


    public function removeNode(Node $node)
    {
        foreach ($this->nodes as $key=>$node) {

        }
    }

    /**
     * @return Node
     */
    public function getL()
    {
        return $this->l;
    }

    /**
     * @param Node $l
     */
    public function setL($l)
    {
        $this->l = $l;
    }

    /**
     * @return Node
     */
    public function getM()
    {
        return $this->m;
    }

    /**
     * @param Node $m
     */
    public function setM($m)
    {
        $this->m = $m;
    }

    /**
     * @return Node
     */
    public function getR()
    {
        return $this->r;
    }

    /**
     * @param Node $r
     */
    public function setR($r)
    {
        $this->r = $r;
    }


}