<?php

use hyper\request;

function checkLoggedUser(request $request)
{
    if (
        strpos($request->path, '/admin') === 0 &&
        !isset($request->user) &&
        !in_array($request->path, ['/admin/login'])
    ) {
        redirect('/admin/login');
    }
}

function checkNotLoggedUser(request $request)
{
    if (
        strpos($request->path, '/admin') === 0 &&
        isset($request->user) &&
        in_array($request->path, ['/admin/login'])
    ) {
        redirect('/admin');
    }
}
