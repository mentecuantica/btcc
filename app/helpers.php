<?php
/**
 * Filename: helpers.php
 */

use Btcc\Models\User;

if (! function_exists('user')) {
    /**
     * Generate a URL to a named route.
     * @return User
     */
    function user()
    {
        return  \Auth::getUser()==NULL ? new \Btcc\Services\User\NullUser() : \Auth::getUser();
    }
}