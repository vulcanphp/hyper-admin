<?php

namespace admin\views;

use admin\admin;
use hyper\request;
use hyper\utils\form;
use hyper\utils\hash;
use hyper\helpers\uploader;

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
            $password = setting('admin_user', 'password', false);
            $user = admin::$instance->getSetup('user', ['name' => 'admin']);
            $attemp_login = false;
            if (!$password || ($user['reset'] ?? false)) {
                if ($request->post('confirm_password') === $request->post('password')) {
                    admin::$instance->settings->set('admin_user', 'password', hash::make($request->post('confirm_password')));
                    $attemp_login = true;
                } else {
                    $error = __('Password Confirmation Failed', true);
                }
            } elseif (hash::validate($request->post('password'), $password)) {
                $attemp_login = true;
            } else {
                $error = __('Incorrect password', true);
            }

            if ($attemp_login) {
                session()->set('logged', true);
                $request->user = $user;
                return redirect(admin_prefix());
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
            return redirect(admin_prefix('menus'));
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
            return redirect(admin_prefix('settings'));
        }
        $form = new form(request: $request, fields: $fields);
        $form->load(admin::$instance->settings->get($setting, '*', []));

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
            return redirect(admin_prefix('setting/' . $setting));
        }

        return admin::$instance->template('setting', ['form' => $form, 'setting' => $setting]);
    }
}
