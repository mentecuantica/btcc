<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/27/2016
 * Time: 5:15 AM
 * Filename: AccountComposer.php
 */

namespace Btcc\Http\ViewComposers;


use Illuminate\Contracts\View\View;
use Illuminate\Routing\Route;

/**
 * Class ViewComposers
 * @package Btcc\Http\ViewComposers
 */
class SubmenuComposer {


    protected $items = [];
    protected $route;

    public function __construct()
    {
       // $this->route = app(Route::class);
       // $this->route = app(Route::class);

    }

    public function compose(View $view)
    {

        $view->with([
            'router'=>$this->route,
            'submenu'=>[],


        ]);
    }
}