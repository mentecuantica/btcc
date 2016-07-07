<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/7/2016
 * Time: 10:00 AM
 * Filename: DashboardController.php
 */

namespace Btcc\Http\Controllers;

/**
 * Class DashboardController
 * @package Btcc\Http\Controllers
 */
class DashboardController extends Controller {

    public function index()
    {
        return view('dashboard.index');
    }
}