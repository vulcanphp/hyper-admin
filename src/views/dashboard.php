<?php

namespace admin\views;

use admin\admin;
use hyper\request;

class dashboard
{
    function index()
    {
        return admin::$instance->template('index');
    }

    function models()
    {
        return admin::$instance->template('models');
    }

    function menus()
    {
        return admin::$instance->template('menus');
    }

    function login(request $request)
    {
        $error = '';
        if ($request->method === 'POST') {
            $user = admin::$instance->getSetup('user', ['name' => 'admin', 'password' => 'admin']);
            if ($request->post('password') === $user['password']) {
                session()->set('logged', true);
                $request->user = $user;
                return redirect('/admin');
            } else {
                $error = __('Incorrect password');
            }
        }
        return admin::$instance->template('auth/login', ['error' => $error]);
    }

    function logout(request $request)
    {
        session()->delete('logged');
        unset($request->user);
        return admin::$instance->template('auth/logout');
    }

    function menu(request $request, string $menu)
    {
        if (($callback = admin::$instance->getSetup('menus', [])[$menu] ?? null) == null) {
            return redirect('/admin/menus');
        }
        if (is_callable($callback)) {
            $content = call_user_func($callback, $request);
        } elseif (is_file($callback) && file_exists($callback)) {
            $content = require $callback;
        } else {
            $content = $callback;
        }
        return admin::$instance->template('menu', ['content' => $content, 'menu' => $menu]);
    }
}
