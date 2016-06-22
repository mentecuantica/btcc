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

    public function index()
    {

    }

    public function phpinfo()
    {
      phpinfo();
    }
    
    



}