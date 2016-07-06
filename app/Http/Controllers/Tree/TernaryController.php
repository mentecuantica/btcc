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
    


}
