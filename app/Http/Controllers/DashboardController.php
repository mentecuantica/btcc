<?php

namespace Btcc\Http\Controllers;

use Btcc\Http\Requests;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        return view('dashboard.index');
    }
}
