<?php

namespace Btcc\Http\Controllers;

use Btcc\Services\BinaryTree;
use Btcc\Http\Requests;
use Illuminate\Http\Request;

class TreeController extends Controller
{

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


    }
    
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBinary()
    {
        $rows =  BinaryTree::getUserTree(7);

        $jsonNodes = BinaryTree::formNestedJson($rows,7);


      


        //dd(json_encode($treeArray));

        return view('tree.index',compact('jsonNodes'));

    }

    public function showBinary($id)
    {
        
        $rows =  BinaryTree::getUserTree($id);

        $jsonNodes = BinaryTree::formNestedJson($rows,$id);

        return view('tree.index',compact('jsonNodes'));

    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function binaryTree()
    {
        $rows =  BinaryTree::getUserTree(\Sentinel::getUser()->getUserId());

        $jsonNodes = BinaryTree::formNestedJson($rows,\Sentinel::getUser()->getUserId());


        return $jsonNodes;

        //dd(json_encode($treeArray));

        return view('tree.index',compact('jsonNodes'));

    }
}
