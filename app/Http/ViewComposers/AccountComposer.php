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
class AccountComposer {


    protected $user;
    protected $partners;
    protected $partnersCount;

    public function __construct()
    {

        /*if (\Auth::user()) {
            $this->user = \Auth::user();
            $this->partnersCount = \Auth::user()->descendants()->count();

            //        $this->partners2 = \Auth::user()->partners;

        } */
    }

    public function compose(View $view)
    {
        $view->with([
            //'partners'=>[],

            'partnersCount'=>$this->partnersCount,
            //'partners'=>$this->partners2,
            
            //'partners1'=>$this->partners1,

        ]);
    }
}