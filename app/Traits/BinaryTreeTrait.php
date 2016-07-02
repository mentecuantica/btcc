<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/31/2016
 * Time: 4:09 PM
 * Filename: BinaryTree.php
 */

namespace Btcc\Traits;

use Btcc\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class BinaryTree
 * @package app\Services
 */
trait BinaryTreeTrait {

    /**
     * @param $userId
     *
     * @return array
     */
    public static function getUserTree($userId)
    {
        $query
            = 'SELECT d.parent_id,d.child_id,d.bt_position,d.level, u.email AS 
        name,u.id FROM bt_get_descendants_with_parent(:id,10) AS d LEFT JOIN users u ON (d.child_id=u.id)';
        $children = \DB::select($query, ['id' => $userId]);

        return $children;
    }

    public static function getUserDescendantsModels($userId)
    {
        return User::hydrate(static::getUserTree($userId));

    }

    /**
     * @param $userId
     *
     * @return array
     */
    public static function generateJsonBinary($userId)
    {
        $rows = static::getUserTree($userId);

        if (count($rows) == 0) {
            // no children

            $parent = new \stdClass();
            $parent->id = $userId;
            $parent->name = user()->email;
            $jsonNodes = json_encode([]);

            return [
                $parent,
                $jsonNodes
            ];
        }

        /**
         * If remove first element from rows, to make it parent
         *      then buildTree fails

         */
        $revesedArray = array_reverse($rows, TRUE);
        $parent = array_pop($revesedArray);
        //unset($rows[0]);

        $jsonNodes = static::formNestedJson($rows, $userId);

        return [
            $parent,
            $jsonNodes
        ];
    }

    /**
     * @param array $elements
     * @param int   $parentId
     *
     * @return array
     */
    public static function buildTree(array $elements, $parentId = 1)
    {

        $branch = [];

        foreach ($elements as $node) {
            $node['text'] = [
                'name'  => $node['name'],
                'title' => '' . $node['name'] . ' ID:' . $node['id'] . ' Level:' . $node['level'],
                'desc'  => $node['bt_position'],

            ];
            $node['link'] = ['href' => url('/tree/show', $node['id'])];
            $node['HTMLclass'] = 'partner';

            if ($node['parent_id'] == $parentId) {
                // $node['HTMLclass']='boss';
                $children = static::buildTree($elements, $node['id']);
                if ($children) {
                    $node['children'] = $children;
                }
                $branch[] = $node;
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


        return json_encode(static::buildTree($items, $parentId));

    }

}