<?php

namespace admin\views;

use admin\admin;
use hyper\request;
use hyper\utils\form;
use hyper\utils\hash;
use hyper\helpers\uploader;

/**
 * views dashboard
 *
 * This class is the main entry point for the admin controller.
 * It handles the dashboard, models, menus, login, logout, and settings pages.
 *
 * @package admin
 * @author Shahin Moyshan <Shahin.moyshan2@gmail.com>
 * @since 1.0.0
 */
class dashboard
{
    use uploader;

    /**
     * Shows the admin dashboard.
     *
     * @return \hyper\response
     */
    function index()
    {
        return admin::$instance->template('index');
    }

    /**
     * Shows the admin models page.
     *
     * @return \hyper\response
     */
    function models()
    {
        return admin::$instance->template('models');
    }

    /**
     * Shows the admin menus page.
     *
     * @return \hyper\response
     */
    function menus()
    {
        return admin::$instance->template('menus');
    }

    /**
     * Handles the admin login process.
     *
     * This function will attempt to log a user in if the request
     * method is POST. If the user is already logged in, they will
     * be redirected to the admin dashboard.
     *
     * @param request $request The current HTTP request.
     * @return \hyper\response
     */
    function login(request $request)
    {
        // Hold the error message.
        $error = '';

        // Attempt to login if the requeest post.
        if ($request->method === 'POST') {
            // Get the password stored in settings.dr 
            $password = setting('admin_user', 'password', false);
            $user = admin::$instance->getSetup('user', ['name' => 'admin']);
            $attemp_login = false;

            // Validate the password.
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

            // Set login into session
            if ($attemp_login) {
                session()->set('logged', true);
                $request->user = $user;
                return redirect(admin_prefix());
            }
        }

        // The rendered login page or a redirect response.
        return admin::$instance->template('auth/login', ['error' => $error]);
    }

    /**
     * Handles the admin logout process.
     *
     * This function deletes the user's session and unsets the user object,
     * effectively logging the user out. It then returns the rendered logout page.
     *
     * @param request $request The current HTTP request.
     * @return \hyper\response
     */
    function logout(request $request)
    {
        session()->delete('logged');
        unset($request->user);

        // return to the logout success page.
        return admin::$instance->template('auth/logout');
    }

    /**
     * Handles the admin menu page.
     *
     * This function takes a request and a menu name,
     * and renders the menu with the given name.
     * If the menu doesn't exist, it redirects to the menus page.
     *
     * @param request $request The current HTTP request.
     * @param string $menu The name of the menu to render.
     * @return \hyper\response
     */
    function menu(request $request, string $menu)
    {
        // Get the menu callback.
        $callback = admin::$instance->getSetup('menus', [])[$menu] ?? null;

        // If the menu doesn't exist, redirect to the menus page.
        if ($callback === null) {
            return $request->accept('application/json') ?
                response()->json(['push' => admin_prefix('menus')]) :
                redirect(admin_prefix('menus'));
        }

        // Render the menu content.
        if (is_callable($callback)) {
            $content = call_user_func($callback, $request);
        } elseif (is_file($callback) && file_exists($callback)) {
            $content = require $callback;
        } else {
            $content = $callback;
        }

        // Render the menu page.
        return admin::$instance->template('menu', ['content' => $content, 'menu' => $menu]);
    }

    /**
     * Handles the admin settings page.
     *
     * This function renders the settings page.
     *
     * @return \hyper\response
     */
    function settings()
    {
        return admin::$instance->template('settings');
    }

    /**
     * Handles the admin setting page.
     *
     * This function renders the settings page for the given setting, validates the
     * form if the request method is POST, and saves the settings to the database.
     *
     * @param request $request The current HTTP request.
     * @param string $setting The name of the setting to render.
     * @return \hyper\response
     */
    function setting(request $request, string $setting)
    {
        // Checl if the requeest is ajax
        $is_ajax = $request->accept('application/json');

        // Get the fields for the setting.
        $fields = admin::$instance->getSetup('settings', [$setting => []]);

        // If the setting doesn't exist, redirect to the settings page.
        if ($fields === null) {
            return $is_ajax ? response()->json(['push' => admin_prefix('settings')]) : redirect(admin_prefix('settings'));
        }

        // Create a new form instance to render form.
        $form = new form(request: $request, fields: $fields);
        $form->load(
            admin::$instance->settings->get($setting, '*', [])
        );

        // If the request is POST and the form is valid, save the settings.
        if ($request->method === 'POST' && $form->validate()) {
            $data = [];

            foreach ($form->getFields() as $field) {
                $name = $field['name'];
                if ($field['type'] === 'file') {
                    $upload = [
                        'uploadDir' => env('upload_dir') . '/settings',
                        'multiple' => $field['multiple'],
                        'extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'html', 'zip', 'pdf', 'doc', 'xlsx', 'csv', 'mp3', 'mp4'],
                        'maxSize' => 1048576 * 20, // 20 MB
                    ];
                    $data[$name] = $this->__doUpload($name, $upload, $field['value']);
                } else {
                    $data[$name] = $field['value'];
                }
            }

            // Set a flalsh message and redirect to the settings page.
            admin::$instance->settings->setup($setting, $data);
            session()->set(
                'success',
                __('Settings for ' . $setting . ' has been saved.', true)
            );

            return $is_ajax ?
                response()->json(['push' => admin_prefix('setting/' . $setting)]) :
                redirect(admin_prefix('setting/' . $setting));
        }

        // Render the setting page.
        return admin::$instance->template('setting', ['form' => $form, 'setting' => $setting]);
    }
}
