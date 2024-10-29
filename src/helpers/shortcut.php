<?php

use admin\admin;
use hyper\utils\drawer;

/** 
 * helpers/shortcut.php
 * 
 * Contains functions that are shortcuts for the admin panel.
 * 
 * @package hyper\helpers
 * @author Shahin Moyshan <shahin.moyshan2@gmail.com>
 */

/**
 * Generates a URL for the admin panel.
 *
 * @param string $prefix
 * @return string
 */
function admin_url($prefix = '')
{
    return url(admin_prefix($prefix));
}

/**
 * Generate a URL prefix for the admin panel.
 *
 * This method will add the $prefix to the admin panel's prefix and trim any
 * trailing slashes.
 *
 * @param string $prefix
 * @return string
 */
function admin_prefix($prefix = '')
{
    return rtrim(admin::$instance->prefix . '/' . ltrim($prefix), '/');
}

/**
 * Returns the current admin instance.
 *
 * @return admin
 */
function admin(): admin
{
    return admin::$instance;
}

/**
 * Retrieve a setting value from a layer.
 *
 * @param string $layer
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function setting(string $layer, string $key, $default = null)
{
    return admin::$instance->settings->get($layer, $key, $default);
}

/**
 * Retrieve the settings drawer.
 *
 * This method returns the settings drawer for the admin panel which
 * contains all the settings for the admin panel.
 *
 * @return drawer
 */
function settings(): drawer
{
    return admin::$instance->settings;
}

/**
 * Returns the JavaScript code for the "fire" script.
 *
 * This method returns the minified JavaScript code for the "fire" script
 * which is used to load the admin panel.
 *
 * @return string
 */
function fire_script(): string
{
    return file_get_contents(__DIR__ . '/../assets/fire.min.js');
}