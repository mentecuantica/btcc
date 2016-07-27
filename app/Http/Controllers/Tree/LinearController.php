<?php

namespace Btcc\Http\Controllers\Tree;

use Btcc\Http\Controllers\Controller;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Http\Requests;
use Btcc\Models\Tree\TreeDecorator;
use Btcc\Models\Tree\TreeLinear;
use Btcc\Models\User;
use Btcc\Utilities\Tree\Ternary\Tree;
use Illuminate\Http\Request;

class LinearController extends Controller
{




    public function index()
    {

        $hierarchy = $this->queryBuilderMethod(\Auth::id());

        $htmlList = static::ulLi($hierarchy);


        return view('tree.linear.index',compact('htmlList'));
    }

    public static function ulLi($nodes) {
        ob_start();
        echo "<ul>";
        foreach($nodes as $root) {
            static::renderNode($root);
        }
        echo "</ul>";
        $result = ob_get_clean();
        return $result;
    }

    public static function renderNode($node) {
        echo "<li>";
        echo "<b>{$node->first_name} {$node->last_name} {$node->email}</b>";

        if ( $node->children()->count() > 0 ) {
            echo "<ul>";
            foreach($node->children as $child) {
                static::renderNode($child);
            }
            echo "</ul>";
        }

        echo "</li>";

    }

    /**
     * @return mixed
     */
    protected function queryBuilderMethod(int $userId)
    {
        return User::find($userId)->linear->descendantsAndSelf()
            ->join('users', 'tree_linear.user_id', '=', 'users.id')
            ->get(['tree_linear.user_id',
               'parent_id','lft','rgt','depth',
               'users.name','first_name',
               'last_name','users.id','users.email'])
            //->get()
            ->toHierarchy();

    }

    /**
     * @return mixed
     */
/*    protected function modelRelationsMethod()
    {
        $ns = TreeLinear::find('1');


        $nl = $ns->descendantsAndSelf()->with('user.profile')
     ->get()->toHierarchy();

        return $nl;
    }*/

}
