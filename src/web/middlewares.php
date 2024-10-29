<?php

use hyper\request;

/** 
 * web/middlewares.php
 * 
 * This file contains middleware functions used to handle user authentication
 * and redirection based on the login status of the user.
 *
 * @package hyper\helpers
 * @author Shahin Moyshan <shahin.moyshan2@gmail.com>
 */


/**
 * This function redirects the user to the login page if it is not logged
 * and it is trying to access a page that is not the login page.
 *
 * @param request $request The current request
 */
function checkLoggedUser(request $request)
{
    if (
        strpos($request->path, admin_prefix()) === 0 &&
        !isset($request->user) &&
        !in_array($request->path, [admin_prefix('login')])
    ) {
        redirect(admin_prefix('login'));
    }
}

/**
 * This function redirects the user to the home page if it is logged
 * and it is trying to access the login page.
 *
 * @param request $request The current request
 */
function checkNotLoggedUser(request $request)
{
    if (
        strpos($request->path, admin_prefix()) === 0 &&
        isset($request->user) &&
        in_array($request->path, [admin_prefix('login')])
    ) {
        redirect(admin_prefix());
    }
}

