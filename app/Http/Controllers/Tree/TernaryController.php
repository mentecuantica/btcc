<?php

namespace Btcc\Http\Controllers\Tree;

use Btcc\Models\User;
use Btcc\Models\Tree\TreeDecorator;
use Btcc\Http\Controllers\Controller;
use Btcc\Utilities\Tree\Ternary\Tree;

class TernaryController extends Controller
{

    

    public function index()
    {
        $userId = \Auth::id();

        //$userOnTop=
        $level = 6;
        $matrixQuery = 'select td.*,u.email AS name from ternary_get_descendants(:id,:lim) AS td
        LEFT JOIN users u ON (td.user_id=u.id)';

        $nodes = \DB::select($matrixQuery,['id'=>$userId,'lim'=>$level]);

        $decorator = TreeDecorator::factory($nodes,'parent_id','user_id');
        $matrixNestedArray = $decorator->toNestedArray($userId);

        $parent = User::find($userId);


        $parent->name = $parent->email;

        \JavaScript::put(['childrenNodes'=>$matrixNestedArray,'parent'=>$parent,'user'=>user(),'tree'=>'ternary']);


        return view('tree.ternary.index');
    }

    public function showTernary()
    {
        $tree = new Tree(Tree::generateFakeData(60));

        $tags = $tree->getUnorderedListHtml();


        return view('tree.ternary.showTernary',compact('tags','tree'));

    }

}
