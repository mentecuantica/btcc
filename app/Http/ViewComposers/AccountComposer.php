<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/27/2016
 * Time: 5:15 AM
 * Filename: AccountComposer.php
 */

namespace Btcc\Http\ViewComposers;


use Btcc\Services\PackageService;
use Illuminate\Contracts\View\View;

/**
 * Class ViewComposers
 * @package Btcc\Http\ViewComposers
 */
class AccountComposer {


    protected $partners;
    protected $partnersCount;
    protected $wallet = [];
    protected $packages;

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     * param [ mixed $args [, $... ]]
     *
     * @param \Btcc\Services\PackageService$packages
     *
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(PackageService$packages)
    {
        $this->packages= collect($packages);



        //$view->with(['packages'=>$packages]);

    }

    public function compose(View $view)
    {
     //   $this->test =  user()->transactions->totalAmount();
        $view->with([
            //'partners'=>[],

            'wallet'=>['balance'=>user()->totalSum,
                       'packageName'=>user()->getPackageAttribute()->name],
            'package'=>[user()->getPackageAttribute()],
            'packages'=>collect($this->packages),
            
            //'partners1'=>$this->partners1,

        ]);

    }
}