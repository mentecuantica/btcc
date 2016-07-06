<?php

namespace Btcc\Http\Controllers\Tree;

use Btcc\Http\Controllers\Controller;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Http\Requests;
use Btcc\Models\Tree\TreeDecorator;
use Btcc\Models\User;
use Btcc\Utilities\Tree\Ternary\Tree;
use Illuminate\Http\Request;

class BinaryController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showBinaryAll()
    {
        $userId = 1;

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

}
