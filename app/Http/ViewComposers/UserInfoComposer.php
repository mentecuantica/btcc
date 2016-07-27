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
use Btcc\Services\PackageService;
use Illuminate\Contracts\View\View;

/**
 * @todo Only for registered users
 *
 * Class ViewComposers
 * @package Btcc\Http\ViewComposers
 */
class UserInfoComposer {


    protected $partners;
    protected $partnersCount;
    //protected $wallet = [];

    protected $packages = [];
    private $isUserLoaded = false;

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     * param [ mixed $args [, $... ]]
     *
     * @param \Btcc\Services\PackageService    $packages
     *
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(User $user)
    {
        if (\Auth::guest() == true) {
            return false;

        }

        $this->isUserLoaded = true;

       // $this->packages= collect($packages);



        //$view->with(['packages'=>$packages]);

    }

    public function compose(View $view)
    {
        if ($this->isUserLoaded == false) {
            return;


        }

        $view->with([
            'profile'=>[
                'package'=>user()->getPackageAttribute(),
            ],

            'wallet'=>[
                'balance'=>user()->totalSum,

            ],
            'stat'=>
            [
                'registeredUsers'=>user()->linear->countPartners(),


            ],

            

        ]);

    }
}