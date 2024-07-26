<?php

use admin\admin;
use hyper\request;

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
