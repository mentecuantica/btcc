<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/31/2016
 * Time: 4:09 PM
 * Filename: BinaryTree.php
 */

namespace Btcc\Services;

use Illuminate\Support\Facades\DB;

/**
 * Class BinaryTree
 * @package app\Services
 */
class BinaryTree {

    /**
     * @param $userId
     *
     * @return array
     */
    public static function getUserTree($userId)
    {
        //$children = \DB::select('select * from getChildren(:id)',['id'=>$userId]);
        $children = \DB::select('SELECT gc.*,tc.name,tc.id as uid FROM getDescendants(:id) as gc, users as tc WHERE gc.child_id = tc.id', ['id' => $userId]);
        \Debugbar::addMessage('Loaded raw:',$children);

        return $children;
    }


    /**
     * @param $userId
     *
     * @return array
     */
    public static function getUser($userId)
    {
        //$children = \DB::select('select * from getChildren(:id)',['id'=>$userId]);
        $children = \DB::select('SELECT gc.*,tc.name,tc.id FROM getDescendants(:id) as gc, users as tc WHERE gc.child_id = tc.id', ['id' => $userId]);

        return $children;
    }


    /**
     * @param array $elements
     * @param int   $parentId
     *
     * @return array
     */
    public static function buildTree(array $elements, $parentId = 1)
    {

        //\Debugbar::addMessage('Elelement raw:',$elements);
        $branch = [];

        foreach ($elements as $element) {
            $element['text'] = [
                'name' => $element['name'],
                'title'=> $element['uid'],
                'desc' => $element['placement'],

            ];
            $element['link']=['href'=>url('/tree/show',$element['uid'])];


            if ($element['parent_id'] == $parentId) {
                $children = static::buildTree($elements, $element['uid']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

       // \Debugbar::addMessage('Final ',$branch);

        return $branch;
    }

    /**
     * @param $nodeList
     *
     * @return string
     */
    public static function formNestedJson($nodeList, int $parentId = 1)
    {

        /**
         * @todo Change to normal
         * convert stdClass to array
         */
        $items = [];
        foreach ($nodeList as $item) {
            $items[] = json_decode(json_encode($item), TRUE);
        }

       // \Debugbar::addMessage('json_decode',$items);

        return json_encode(static::buildTree($items, $parentId));

    }


    /**
     * 1. Add user
     *      1.1 Choose parent, position (L,R)
     *          1.1.3 Check available position is free - L or R
     *               If both free -
     *                    take a place at choosed position
     *               If only one free
     *                    take place at that position
     *                    or
     *                    take child position and use it as a parent and REPEAT 1.1
     * 2. Get user tree
     *     Check relations
     *     Can see only his partners ?
     *       or
     *     Can see all his childs?
     * 3. Integrity check
     *      LEFT RIGHT rules
     *      PARENT CHILD existence
     * @todo Table tree strucutree
     * @todo Use SQL functions, SP or PHP logic ?
     */

    /**
     *  1. If the tree is empty, insert new_node as the root node (obviously!)
     * 2. while (tree is NOT empty):
     * 2a. If (current_node is empty), insert it here and stop;
     * 2b. Else if (new_node > current_node), try inserting to the right
     * of this node (and repeat Step 2)
     * 2c. Else if (new_node < current_node), try inserting to the left
     * of this node (and repeat Step 2)
     * 2d. Else value is already in the tree
     */
}