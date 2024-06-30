<?php

namespace admin\views;

use admin\admin;
use hyper\helpers\uploader;
use hyper\request;
use hyper\utils\form;

class dashboard
{
    use uploader;

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

    function settings()
    {
        return admin::$instance->template('settings');
    }

    function setting(request $request, string $setting)
    {
        if (($fields = admin::$instance->getSetup('settings', [])[$setting] ?? null) == null) {
            return redirect('/admin/settings');
        }
        $form = new form(request: $request, fields: $fields);
        $form->load(admin::$instance->settings->get($setting, '*'));

        if ($request->method === 'POST' && $form->validate()) {
            $data = [];
            foreach ($form->getFields() as $field) {
                $name = $field['name'];
                if ($field['type'] === 'file') {
                    $upload = [
                        'uploadDir' => env('upload_dir') . '/settings',
                        'multiple' => $field['multiple'],
                        'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'html', 'zip', 'pdf', 'doc', 'xlsx', 'csv', 'mp3', 'mp4'],
                        'maxSize' => 1048576 * 20, // 20 MB
                    ];
                    $data[$name] = $this->__doUpload($name, $upload, $field['value']);
                } else {
                    $data[$name] = $field['value'];
                }
            }
            admin::$instance->settings->setup($setting, $data);
            session()->set('success', __('Settings for ' . $setting . ' has been saved.', true));
            return redirect('/admin/setting/' . $setting);
        }

        return admin::$instance->template('setting', ['form' => $form, 'setting' => $setting]);
    }
}
