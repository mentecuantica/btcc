<?php
/**
 * Filename: helpers.php
 */

if (! function_exists('user')) {
    /**
     * Generate a URL to a named route.
     * @return User
     */
    function user()
    {
        return \Sentinel::getUser();
    }
}