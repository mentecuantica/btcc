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
    
    
    
    

   
}
