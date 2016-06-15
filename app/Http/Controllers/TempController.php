<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/26/2016
 * Time: 12:27 AM
 * Filename: TestController.php
 */

namespace Btcc\Http\Controllers;
use Btcc\Http\Controllers\Controller;
use Btcc\Models\Temp\UserLevel;
use Btcc\Models\User;
use Btcc\Services\SystemWallet;

/**
 * Class TestController
 * @package app\Http\Controllers
 */
class TempController extends Controller {

    public function initTree()
    {
        $root = UserLevel::create(['user_id' => \Auth::id(), 'binary_flag' =>'L']);

        return $root;
    }

    public function phpinfo()
    {
      phpinfo();
    }
    
    public function testTree() {

        $user = \Sentinel::getUser();

        /**
         * @var User $user
         */
        $treeBinary = $user->getTreeBinary();
        $treeLinear = $user->getTreeLinear();

        dd($treeBinary,$treeLinear);
    }


    /**
     * App singleton returns only instance
     *
     * @return mixed
     */
    public function globalSingletonBinding()
    {
        return app('SystemWallet');

    }

    /**
     * DI returns only new instance
     *
     * @param \Btcc\Services\SystemWallet $systemWallet
     *
     * @return \Btcc\Services\SystemWallet
     */
    public function globalDISingletonBinding(SystemWallet $systemWallet)
    {
        return $systemWallet;

    }

}