<?php

use admin\views\dashboard;
use admin\views\bread;

return [
    ['path' => '/admin', 'callback' => [dashboard::class, 'index']],
    ['path' => '/admin/login', 'method' => ['GET', 'POST'], 'callback' => [dashboard::class, 'login']],
    ['path' => '/admin/logout', 'method' => ['POST'], 'callback' => [dashboard::class, 'logout']],
    ['path' => '/admin/models', 'callback' => [dashboard::class, 'models']],
    ['path' => '/admin/menus', 'callback' => [dashboard::class, 'menus']],
    ['path' => '/admin/menu/{menu}', 'method' => ['GET', 'POST'], 'callback' => [dashboard::class, 'menu']],
    ['path' => '/admin/model/{model}', 'callback' => [bread::class, 'browse']],
    ['path' => '/admin/model/{model}/delete', 'method' => ['POST'], 'callback' => [bread::class, 'delete']],
    ['path' => '/admin/model/{model}/add', 'method' => ['GET', 'POST'], 'callback' => [bread::class, 'add']],
    ['path' => '/admin/model/{model}/{id}/change', 'method' => ['GET', 'POST'], 'callback' => [bread::class, 'change']],
];
