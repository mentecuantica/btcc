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

/**
 * Class ViewComposers
 * @package Btcc\Http\ViewComposers
 */
class SubmenuComposer {


    protected $items = [];

    public function __construct()
    {

    }

    public function compose(View $view)
    {
        
        $view->with([
            'submenu'=>[],


        ]);
    }
}