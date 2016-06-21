<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/21/2016
 * Time: 2:25 PM
 * Filename: Tree.php
 */

namespace Btcc\Utilities\Tree\Ternary;

use Illuminate\Support\Collection;

/**
 * Class Tree
 * @package Btcc\Utilities\Tree\Ternary
 */
class Tree {

    /**
     * @var array
     */
    public static $sourceElements;

    /**
     * @var Collection
     */
    protected $elements;

    /**
     * @var Node
     */
    public $root;

    /**
     * @var \SplStack
     */
    protected $addedStack;

    protected static $nodesFlatCollection;

    protected $nodes = [];

    /**
     * Tree constructor.
     *
     * @param $elements
     * @param $rootId
     */
    public function __construct($elements = [])
    {
        static::$sourceElements = $elements;

        $this->elements = collect($elements);

        $this->init();

        $this->generateTernaryTreeStructure();

        static::$nodesFlatCollection = Node::iterateRecursive($this->root, collect([]));

    }

    /**
     * @return Collection
     */
    public static function getNodesFlatCollection()
    {
        /*        if (!self::$nodesFlatCollection instanceof Collection) {
                    Node::iterateRecursive($this->root, collect([]));
                }*/
        return self::$nodesFlatCollection;
    }

    /**
     * @param mixed $nodesFlatCollection
     */
    public static function setNodesFlatCollection($nodesFlatCollection)
    {
        self::$nodesFlatCollection = $nodesFlatCollection;
    }

    public function addToFreeNode($level)
    {
        $result = $this->getFreeNode(NULL, $level - 1);

        if ($result instanceof Node) {
            for ($i = 1; $i <= 3; $i++) {
                if ($this->elements->isEmpty()) {
                    return TRUE;
                    // NO MORE NODES!!
                }
                if ($result->hasFreePositions()) {
                    $first = $this->elements->shift();
                    $newNode = Node::create($first['id'], $first, $result);
                    $result->addNode($newNode);
                    //$this->addedStack->push($newNode);
                }
            }

            return $this->addToFreeNode($level);
        }

        else {
            return FALSE;
        }

    }

    /**
     * Add flag that level is full
     *
     * @param \Btcc\Utilities\Tree\Ternary\Node $startNode
     * @param                                   $level
     * @param null                              $levelLimit
     *
     * @return int
     * @internal param \Illuminate\Support\Collection $nodes
     * @internal param \Illuminate\Support\Collection $chunksToParse
     */
    public function buildNodesRecursive(Node $startNode, $level, $levelLimit = NULL)
    {
        $success = 0;
        /**
         * Fields only 3
         */
        for ($i = 1; $i <= 3; $i++) {
            if ($startNode->hasFreePositions() && !$this->elements->isEmpty()) {
                $first = $this->elements->shift();
                $newNode = Node::create($first['id'], $first, $startNode);
                $startNode->addNode($newNode);
                $success++;
            }
        }
        $newLevel = $level + 1;
        $inner = 0;

        if ($newLevel === $levelLimit) {
            //return;

        }
        $subs = function ($nodes) use ($level, $levelLimit) {

        };

        /**
         * 0 level, 3 childa and goto children

         */
        /* if ($startNode->isRoot()) {

             foreach ($startNode->getNodes() as $nodeToCheck) {
                 $this->buildNodesRecursive($nodeToCheck,$newLevel, $newLevel);
             }

         }*/

        /**
         * Идём по новым нодам, углубляемся, а их как раз 3.
         * Надо бы перейти на следующую ноду
         * например $buildNodesRecursive($node->get

         */
        if ($startNode->getLevel() >= 1) {
            \Log::warning('Start level is >=1');
            /**
             * @var Node $startNode
             */
            $parent = $startNode->getParent();

            if ($parent->getCount() == 3) {
                \Log::warning('Parent is full');

                /* }
                 if ($freeExists) {*/
                \Log::warning('free exists is 0', [$newLevel]);
                $this->buildNodesRecursive($parent, $level, $newLevel + 1);

            }
        }

        foreach ($startNode->getNodes() as $node) {

            if ($newLevel == $levelLimit) {
                break;

            }
            if ($node->hasFreePositions()) {
                $result = $this->buildNodesRecursive($node, $newLevel, $newLevel + 1);
                if ($result > 0) {
                    $inner = $inner + $result;
                }
                else {
                    return $result + $success;
                }
            }

        }
        if ($inner == 0) {
            \Log::warning('inner is 0', [$newLevel]);

        }

        if (Node::isLevelFull($newLevel)) {
            \Log::warning('new Level is full', [$newLevel]);

        }

        return $success;

    }

    /**
     * Hard recursive
     *
     * @param null $startNodes
     * @param null $level
     *
     * #todo replace it with Node::IterarateRecusive
     *
     *
     * @return \Btcc\Utilities\Tree\Ternary\Node|mixed|null
     */
    public function getFreeNode($startNodes = NULL, $level = NULL)
    {

        $toStart = $this->nodes;
        if (is_array($startNodes)) {
            $toStart = $startNodes;
        }

        $freeNode = NULL;
        foreach ($toStart as $node) {
            /**@var Node $node * */
            if ($node->hasFreePositions() && $node->getLevel() == $level) {
                // $freeNode = $node;

                return $node;
            }
            else {
                $result = $this->getFreeNode($node->getNodes(), $level);
                if ($result !== NULL) {
                    return $result;
                }
            }
        }

        return $freeNode;
    }

    /**
     * @param $firstLevel
     */
    public function addRootNode()
    {
        $rootNode = Node::create(1, ['Name' => 'Root node'], NULL);

        $this->root = $rootNode;

        $this->nodes[] = $this->root;
    }

    protected $maxLevel;

    /**
     * @var Collection
     */
    //protected $nodes;
    public function init()
    {
        $this->totalCount = $this->elements->count();

        $this->maxLevel = $this->getMaximumLevels();

        $this->nodes = collect($this->nodes);
        $this->addedStack = new \SplStack();
    }

    public function hasNodes()
    {
        // TODO: Implement hasNodes() method.
    }

    public function getNodes()
    {
        return $this->nodes;
    }

    public function countNodesByLevel($level = 1)
    {
        return $this->getNodesByLevel($level)->count();

    }

    /**
     * @param int $level
     *
     * @return Collection
     */
    public function getNodesByLevel($level = 1)
    {
        return Node::iterateRecursive($this->root, collect([]))->filter(function (Node $item, $key) use ($level) {
            if ($item->getLevel() == $level) {
                //dump($item,$key);
                return TRUE;
            }

            return FALSE;
        });
    }

    public function addNode(NodeAdv $newNode)
    {
        // TODO: Implement addNode() method.
    }

    public function outputUlLi()
    {
        $this->iterateRecursive($this->getNodes());
    }

    public function iterateRecursive($nodes)
    {
        echo '<ul>';
        foreach ($nodes as $node) {
            echo '<li>';
            /**@var Node $node * */
            if ($node->isRoot()) {
                echo "Root";
            }
            else {
                echo $node;
            }
            if ($node->hasNodes()) {
                $this->iterateRecursive($node->getNodes());
            }
            echo '</li>';
        }
        echo '</ul>';

    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    public function getMaximumLevels()
    {

        $totalCount = $this->getTotalCount();

        $maxLevel = 0;

        $sum = 0;
        foreach (static::$levelsPrecalcs as $level => $maxItems) {
            $sum = $sum + $maxItems;
            if ($sum >= $totalCount) {
                $maxLevel = $level;
                $maxPossibleItems = $maxItems;
                break;
            }
        }

        return $maxLevel;
    }

    /**
     * @return string
     */
    function __toString()
    {
        $str = sprintf("This ternary tree have total: %d, max level: %d ", $this->totalCount, $this->maxLevel);

        return $str;
    }

    /**
     * @var integer
     */
    protected $totalCount;

    public static $levelsPrecalcs
        = [
            1  => 3,
            2  => 9,
            3  => 27,
            4  => 81,
            5  => 243,
            6  => 729,
            7  => 2187,
            8  => 6561,
            9  => 19683,
            10 => 59049,
            11 => 177147,
            12 => 531441,
            13 => 1594323,
            14 => 4782969,
            15 => 14348907,
            16 => 43046721,
            17 => 129140163,
            18 => 387420489,
            19 => 1162261467,
            20 => 3486784401,
            21 => 10460353203,
            22 => 31381059609,
            23 => 94143178827,
            24 => 282429536481,
        ];

    /**
     * @return bool
     */
    protected function buildByFreeNodes()
    {
        $level = 0;
        for ($i = 1; $i < $this->maxLevel; $i++) {
            $isFull = Node::isLevelFull($i);

            if (!$isFull) {
                \Log::warning('Level not full', [$i]);

                $level = $i;
                $this->addToFreeNode($level);

            }

        }

        $result = $this->addToFreeNode($level + 1);

        return $result;
    }

    protected function generateTernaryTreeStructure()
    {
        $this->addRootNode();
        $this->buildNodesRecursive($this->root, 0);
        $this->buildByFreeNodes();
    }

}