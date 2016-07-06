<?php

namespace Btcc\Http\Controllers;

use Btcc\Models\Tree\TreeBinary;
use Btcc\Http\Requests;
use Btcc\Models\User;
use Btcc\Utilities\Tree\Ternary\Tree;
use Illuminate\Http\Request;

class TreeController extends Controller
{

    

    public function index()
    {
        return view('tree.index',[]);
    }
    
    public function showLinear()
    {
        /**
         * 1. Выбрать
         *
         * $user->linear->getDescendants
         *
         *
         *
         */

        $currentUser = \Auth::user();

        $nl = User::find(1)->linear->descendants()
            ->join('users','tree_linear.user_id','=','users.id')
            ->get()
            ->toHierarchy();


        dd($nl);
       // static::ulLi($nl);
        //$array = $nl->toArray();

        //dd($array);


       // echo \Html::ul($array);

       // return view('tree.linear',[]);
    }

    public static function ulLi($nodes) {
        echo "<ul>";
        foreach($nodes as $root) {
            static::renderNode($root);
        }
        echo "</ul>";
    }

    public static function renderNode($node) {
        echo "<li>";
        echo "<b>{$node->user->name}</b>";

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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showBinaryAll()
    {
        $userId = 0;

        list($parent, $jsonNodes) = TreeBinary::generateJsonBinary($userId);

        return view('tree.indexBinary',compact('jsonNodes','parent'));

    }

    public function showBinaryJson()
    {


        $userId = 0;
        $rows =  TreeBinary::getUserTree($userId);

        $jsonNodes = TreeBinary::formNestedJson($rows,$userId);

        return $jsonNodes;

    }


    public function showBinary($id)
    {
        
        
        
        $rows =  TreeBinary::getUserTree($id);

        $jsonNodes = TreeBinary::formNestedJson($rows,$id);

        return view('tree.showBinary',compact('jsonNodes','id'));

    }


    public function showBinaryFree($id)
    {


        $usersPlainCollection = TreeBinary::getUserDescendantsModels($id);

        $parentUser =$usersPlainCollection->first();


        $usersNestedArray = TreeBinary::buildNestedUserArray($usersPlainCollection,$parentUser->parent_id);

        \JavaScript::put(['usersNestedArray'=>$usersNestedArray]);

        return view('tree.showBinaryFree');

    }

    public function showTernary()
    {
        $tree = new Tree(Tree::generateFakeData(60));

        $tags = $tree->getUnorderedListHtml();


        return view('tree.showTernary',compact('tags','tree'));
        
    }
}
