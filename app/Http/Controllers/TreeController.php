<?php

namespace Btcc\Http\Controllers;

use Btcc\Services\BinaryTree;
use Btcc\Http\Requests;
use Illuminate\Http\Request;

class TreeController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows =  BinaryTree::getUserTree(7);

        $jsonNodes = BinaryTree::formNestedJson($rows);


      


        //dd(json_encode($treeArray));

        return view('tree.index',compact('jsonNodes'));

    }

    public function show($id)
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
        $rows =  BinaryTree::getUserTree(7);

        $jsonNodes = BinaryTree::formNestedJson($rows,7);


        return $jsonNodes;

        //dd(json_encode($treeArray));

        return view('tree.index',compact('jsonNodes'));

    }
}
