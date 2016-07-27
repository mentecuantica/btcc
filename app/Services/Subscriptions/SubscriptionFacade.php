<?php
namespace Btcc\Services\Subscriptions;


use Illuminate\Support\Facades\Facade;

/**
 * Class SubscriptionFacade
 * @package Btcc\Services\Subscriptions
 */
class SubscriptionFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'subscription';
    }
}