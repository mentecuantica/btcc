<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/2/2016
 * Time: 5:42 AM
 * Filename: TreeDecorator.php
 */

namespace Btcc\Models\Tree;

use Illuminate\Support\Collection;
use SebastianBergmann\Exporter\Exporter;
use TypiCMS\NestableTrait;

/**
 * Class TreeDecorator
 * @package Btcc\Models\Tree
 */
class TreeDecorator implements TreeDecorable{

    use NestableTrait;



    const NAMES = ['parent_id','user_id','children'];

    protected static $attributes = self::NAMES;


    protected $items;

    /**
     * @var Exporter
     */
    protected static $e;

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     * param [ mixed $args [, $... ]]
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct($items)
    {
        static::$e = new Exporter();
        $this->items=$items;

    }

    public static function factory($items = [],string ...$names)
    {
        if (count($names)>3) {
            $names = array_slice($names,0,3,true);
        }

        foreach ($names as $key=>$name) {
            static::$attributes[$key]=$name;
        }



        return (new static($items));
    }

    public function setPlainArray($items)
    {
        $this->items = $items;
    }

    public function setPlainModels($items)
    {
        $this->items = $items;
    }




    public function getNestedArray()
    {
        // TODO: Implement getNestedArray() method.
    }

    public function getNestedJson()
    {
        // TODO: Implement getNestedJson() method.
    }

    public function getNestedModels()
    {
        // TODO: Implement getNestedModels() method.
    }

    public function getPlainArray()
    {
        // TODO: Implement getPlainArray() method.
    }

    public function getPlainJson()
    {
        // TODO: Implement getPlainJson() method.
    }

    public function getPlainModels()
    {
        // TODO: Implement getPlainModels() method.
    }

    public function toNestedArray($topNodeId, $objElemToArray = FALSE)
    {
        if ($objElemToArray===FAlSe) {
            return static::buildNestedArray($this->items,$topNodeId);
        }


        return static::buildNestedArraySmarty(static::stdClassToArray($this->items),$topNodeId);
    }

    /**
     * @param $objects
     *
     * @return  Collection
     */
    public static function stdClassToArray($objects = [])
    {
        return collect($objects)->map(function($item,$key) {
            return static::$e->toArray($item);
        });

    }

    /**
     * @param $objects
     *
     * @return Collection
     */
    public static function stdClassToArray2($objects = [])
    {
         return collect($objects)->transform(function($item,$key) {
            return static::$e->toArray($item);
        });
    }



    /**$c
     * @param mixed $users
     * @param int   $parentId
     *
     * @return array
     */
    public static function buildNestedArray($nodes, $parentId = 1)
    {
        $newNode = [];
        foreach ($nodes as $node) {
            if (object_get($node,static::p())==$parentId) {

                $children = static::buildNestedArray($nodes, object_get($node,static::c()));
                if ($children) {
                    data_set($node,static::n(),$children);
                }
                $newNode[] = $node;
            }
        }
        return $newNode;
    }

    public static function buildNestedArraySmarty($nodes, $parentId = 1)
    {
        $newNode = [];
        foreach ($nodes as $node) {
            if ($node[static::p()]==$parentId) {
                $children = static::buildNestedArraySmarty($nodes,$node[static::c()]);
                if ($children) {
                    $node[static::n()]=$children;
                }
                $newNode[] = $node;
            }
        }
        return $newNode;
    }

    public function toNestedArrayOtro($nodes, $topNodeId)
    {
        return static::buildNestedArrayOtro(static::stdClassToArray($nodes),$topNodeId);
    }


    public static function buildNestedArrayOtro($nodes, $parentId = 1)
    {
        $newNode = collect([]);
        foreach ($nodes as $node) {
            if ($node[static::p()]==$parentId) {
                $children = static::buildNestedArrayOtro($nodes,$node[static::c()]);
                if ($children) {
                    $node[static::n()]=$children;
                }
                $newNode->put($node[static::c()],$node);
            }
        }
        return $newNode;
    }



    public static function p()
    {
        return static::$attributes[0];
    }

    public static function c()
    {
        return static::$attributes[1];
    }

    public static function n()
    {
        return static::$attributes[2];
    }
    



}