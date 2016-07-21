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
    public function index()
    {
        $userId = 1;
        $rows = $this->treeRepo->binaryChildren($userId,100)->joinUsers()->get();

        $parent = TreeBinary::extractParentRowJson($rows,$userId);

        $jsonNodes = TreeBinary::generateJson($rows, $userId);

       // dump($jsonNodes);
        return view('tree.binary.indexBinary',compact('jsonNodes','parent'));

    }



    public function show($id)
    {


        $rows = $this->treeRepo->binaryChildren($id,100)->joinUsers()->get();

        $parent = TreeBinary::extractParentRowJson($rows,$id);
        $jsonNodes  = TreeBinary::generateJson($rows,$id);

        return view('tree.binary.showBinary',compact('jsonNodes','id'));

    }



}
