<?php
/**
 * Author: Yuri Efimov
 * User: octopus
 * Date: 5/25/2016
 * Time: 8:24 PM
 * Filename: SystemWallet.php
 */

namespace Btcc\Services;
use Btcc\Models\User;

/**
 * @todo Use it as singleton via App::??
 * @todo use API
 *
 * Class SystemWallet
 * @package app\Services
 */
class SystemWallet {

    protected $totalAmount = 0;

    /**
     * SystemWallet constructor.
     *
     * @param int $initial
     */
    public function __construct($initial = 100)
    {
        $this->add($initial, \Auth::guest() ? (new User()) : \Auth::user() );
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->totalAmount;
    }

    /**
     *
     * @param integer                                 $amount
     * @param \Illuminate\Foundation\Auth\User $from
     */
    public function add($amount,User $from)
    {
        $this->totalAmount = $amount + $this->totalAmount;
    }

    /**
     * @param  integer                                $amount
     * @param \Illuminate\Foundation\Auth\User $from
     */
    public function withdraw($amount,User $from)
    {
        $this->totalAmount = $this->totalAmount - $amount;
    }

    function __toString()
    {
        return json_encode(['balance'=>$this->getBalance()]);
    }

}