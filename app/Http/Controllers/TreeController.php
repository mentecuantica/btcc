<?php

namespace Btcc\Http\Controllers;

use Btcc\Models\Tree\TreeBinary;
use Btcc\Http\Requests;
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

        $currentUser = \Sentinel::getUser();


        return view('tree.indexLinear',[]);
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

        return view('tree.indexBinary',compact('jsonNodes'));

    }


    public function showBinary($id)
    {
        
        
        
        $rows =  TreeBinary::getUserTree($id);

        $jsonNodes = TreeBinary::formNestedJson($rows,$id);

        return view('tree.indexBinary',compact('jsonNodes'));

    }

}
