<?php

use admin\admin;
use admin\core\drawer;

function admin_url($prefix = '')
{
    return url(admin_prefix($prefix));
}

function admin_prefix($prefix = '')
{
    return admin::$instance->prefix . '/' . ltrim($prefix);
}

function admin(): admin
{
    return admin::$instance;
}

function setting(string $layer, string $key, $default = null)
{
    return admin::$instance->settings->get($layer, $key, $default);
}

function settings(): drawer
{
    return admin::$instance->settings;
}
