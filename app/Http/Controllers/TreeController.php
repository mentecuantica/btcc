<?php

namespace Btcc\Http\Controllers;

use Btcc\Services\BinaryTreeHelper;
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
        $rows =  BinaryTreeHelper::getUserTree(7);

        $jsonNodes = BinaryTreeHelper::formNestedJson($rows,7);


      


        //dd(json_encode($treeArray));

        return view('tree.index',compact('jsonNodes'));

    }

    public function showBinary($id)
    {
        
        
        
        $rows =  BinaryTreeHelper::getUserTree($id);

        $jsonNodes = BinaryTreeHelper::formNestedJson($rows,$id);

        return view('tree.index',compact('jsonNodes'));

    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function binaryTree()
    {
        $rows =  BinaryTreeHelper::getUserTree(\Sentinel::getUser()->getUserId());

        $jsonNodes = BinaryTreeHelper::formNestedJson($rows,\Sentinel::getUser()->getUserId());


        return $jsonNodes;

        //dd(json_encode($treeArray));

        return view('tree.index',compact('jsonNodes'));

    }
}
