<?php

namespace Btcc\Http\Controllers\Tree;

use Btcc\Http\Controllers\Controller;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Http\Requests;
use Btcc\Models\Tree\TreeDecorator;
use Btcc\Models\User;
use Btcc\Utilities\Tree\Ternary\Tree;
use Illuminate\Http\Request;

class TernaryController extends Controller
{

    

    public function index()
    {
        $userOnTop=1;
        $level = 6;
        $matrixQuery = 'select td.*,u.email AS name from ternary_get_descendants(:id,:lim) AS td
        LEFT JOIN users u ON (td.user_id=u.id)';

        $nodes = \DB::select($matrixQuery,['id'=>$userOnTop,'lim'=>$level]);

        $decorator = TreeDecorator::factory($nodes,'parent_id','user_id');
        $matrixNestedArray = $decorator->toNestedArray($nodes,$userOnTop);

        $parent = User::find(1);
        $parent->name = $parent->email;
        //$jsonNodes = json_encode($matrixNestedArray);

        \JavaScript::put(['childrenNodes'=>$matrixNestedArray,'parent'=>$parent,'user'=>user()]);


        return view('tree.ternary.index');
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
