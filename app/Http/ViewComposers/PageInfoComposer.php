<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/27/2016
 * Time: 5:15 AM
 * Filename: AccountComposer.php
 */

namespace Btcc\Http\ViewComposers;


use Btcc\Models\User;
use Illuminate\Contracts\View\View;

/**
 * @todo Only for registered users
 *
 * Class ViewComposers
 * @package Btcc\Http\ViewComposers
 */
class PageInfoComposer {


    protected $pageInfo;

    /**
     *
     * @param \Btcc\Models\User $user
     *
     * @internal param \Btcc\Services\PackageService $packages
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(User $user)
    {
        if (\Auth::guest() == true) {
            return false;

        }
        $this->pageInfo = [
            'title'=>'BTCC',

        ];
        $this->isUserLoaded = true;

    }

    public function compose(View $view)
    {
        if ($this->isUserLoaded == false) {
            return;


        }

        $view->with('pageInfo',[
            $this->pageInfo
        ]);

            



    }
}