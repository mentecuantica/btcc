<?php

namespace Btcc\Http\Controllers;

use Illuminate\Http\Request;

use Btcc\Http\Requests;

/**
 * Static pages
 *
 * Class PagesController
 * @package Btcc\Http\Controllers
 */
class PagesController extends Controller {

    public function faq()
    {
        return view('pages.faq');
    }
}
