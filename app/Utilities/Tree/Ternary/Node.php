<?php

namespace Btcc\Utilities\Tree\Ternary;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class Node
 * @package Btcc\Utilities\Tree\Ternary
 */
class Node extends Composite implements Jsonable, \JsonSerializable, Arrayable{

    use TernaryTrait;

    protected $id;
    protected $level;
    protected $data;
    protected $parent;



    public static function create($id, $data,$parent) {
        $new = new self();

        if ($parent!==NULL) {

            $new->parent = $parent;
            $new->level = $parent->level + 1;

        }
        else {
            $new->level = 0;
        }

        $new->setData($data);
        $new->id = $id;
        return $new;
    }

    /**
     * @return bool
     */
    public function isRoot() {
        if ($this->parent===NULL && $this->level==0) {
            return true;
        }
        return false;
    }


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        //unset($data['id']);
        $this->data = $data;
    }

    function __toString()
    {
        $str = sprintf("%s, %s", $this->id,$this->data['name'] );
        return $str;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return array of Node
     */
    public function getParents() {
        $parents = [];
        $parent = $this->getParent();
        for ($i=$this->getLevel();$i>0;$i--) {

            $parents[$i] = $parent;

            $parent = $parent->getParent();
            if ($parent==NULL) {
                break;
            }

        }

        return $parents;

    }



    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    function jsonSerialize()
    {

        return $this->toArray();

    }

    /**
     * @todo limits on levels
     *
     * @return array
     */
    public function toArray()
    {
        $asArray = [
            'id'=>$this->id,
            'parent_id'=> $this->isRoot() ? NULL : $this->getParent()->id,
            'level'     => $this->getLevel(),
            'data'=>$this->data,
            'children'=> collect($this->nodes)->toArray(),

        ];

        return $asArray;
    }

}