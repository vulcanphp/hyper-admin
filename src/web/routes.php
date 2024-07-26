<?php

use admin\views\dashboard;
use admin\views\bread;

return [
    ['path' => '', 'callback' => [dashboard::class, 'index']],
    ['path' => '/login', 'method' => ['GET', 'POST'], 'callback' => [dashboard::class, 'login']],
    ['path' => '/logout', 'method' => ['POST'], 'callback' => [dashboard::class, 'logout']],
    ['path' => '/settings', 'callback' => [dashboard::class, 'settings']],
    ['path' => '/setting/{setting}', 'method' => ['GET', 'POST'], 'callback' => [dashboard::class, 'setting']],
    ['path' => '/models', 'callback' => [dashboard::class, 'models']],
    ['path' => '/menus', 'callback' => [dashboard::class, 'menus']],
    ['path' => '/menu/{menu}', 'method' => ['GET', 'POST'], 'callback' => [dashboard::class, 'menu']],
    ['path' => '/model/{model}', 'callback' => [bread::class, 'browse']],
    ['path' => '/model/{model}/delete', 'method' => ['POST'], 'callback' => [bread::class, 'delete']],
    ['path' => '/model/{model}/add', 'method' => ['GET', 'POST'], 'callback' => [bread::class, 'add']],
    ['path' => '/model/{model}/{id}/change', 'method' => ['GET', 'POST'], 'callback' => [bread::class, 'change']],
];
