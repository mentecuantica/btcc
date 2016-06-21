<?php


namespace Btcc\Utilities\Tree\Ternary;

use Illuminate\Support\Collection;

trait TernaryTrait {
    protected static $stat = [];

    /**
     * @param     $level
     * @param int $increment
     */
    public static function incByLevel($level, $increment = 1)
    {
        static::initStat();
        $current = static::$stat[ $level ] + $increment;
        static::$stat[ $level ] = $current;
    }

    /**
     * @return array
     */
    public static function getStat()
    {
        return static::$stat;
    }

    public static function getLevelStat($level = 1)
    {
        return static::$stat[ $level ];
    }

    protected static function initStat()
    {
        if (count(static::$stat) > 0) {
            return TRUE;
        }

        $stat = [];
        for ($level = 0; $level <= 25; $level++) {
            $stat[ $level ] = 0;
        }
        static::$stat = $stat;
    }

    public static function isLevelFull($level = 1)
    {
        $realCount = static::getLevelStat($level);
        $maxCount = Tree::$levelsPrecalcs[ $level ];
        if ($realCount == $maxCount) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param \Btcc\Utilities\Tree\Ternary\Node $node
     * @param array|\Illuminate\Support\Collection                                  $items
     *
     * @return \Illuminate\Support\Collection
     */
    public static function iterateRecursive(Node $node, Collection $items)
    {

        $itemsCollect = $items;
        $itemsCollect->put($node->id, $node);

        foreach ($node->getNodes() as $subNode) {
            static::iterateRecursive($subNode, $itemsCollect);

            $itemsCollect->put($subNode->id, $subNode);

        }

        return $itemsCollect;
    }
}