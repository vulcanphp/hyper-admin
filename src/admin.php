<?php

namespace admin;

use hyper\response;
use hyper\template;
use hyper\application;
use admin\core\modelView;
use hyper\request;
use hyper\session;
use Exception;
use hyper\utils\drawer;

/**
 * Class admin
 * 
 * Hyper Admin is a web interface that allows you to manage your
 * Hyperframe application. It allows you to manage your models,
 * settings, menus, and other things.
 *
 * @author  Shahin Moyshan <shahin.moyshan2@gmail.com>
 * @copyright Copyright (c) 2024, Shahin Moyshan
 * @license  MIT
 * @package admin
 * @since 1.0.0
 */
class admin
{
    /**
     * Singleton instance of the admin class.
     *
     * @var admin
     */
    public static admin $instance;

    /**
     * Setup configuration array for enqueuing assets.
     *
     * @var array
     */
    private array $setup = [
        'enque' => [
            'css' => [__DIR__ . '/assets/app.css'],
            'js' => [__DIR__ . '/assets/app.js'],
        ],
    ];

    /**
     * Prefix for admin routes.
     *
     * @var string
     */
    public string $prefix;

    /**
     * Drawer instance for settings management.
     *
     * @var drawer
     */
    public drawer $settings;

    /**
     * Creates a new instance of the admin class.
     *
     * @return void
     */
    public function __construct()
    {
        self::$instance = $this;
    }

    /**
     * This method is called when the admin is first loaded.
     * and sets the prefix for admin routes.
     *
     * @param application $app The application instance.
     * @return void
     */
    public function setup(application $app): void
    {
        // Set up the drawer for settings management,
        $this->settings = new drawer(app_dir('settings.dr'));
        $this->prefix = $app->env['admin_prefix'];

        if (strpos($app->request->path, $this->prefix) === 0) {

            // If the admin is configured in the environment,
            // merge the configuration with the default setup.
            if (isset($app->env['admin'])) {
                $this->setup = array_merge($this->setup, require $app->env['admin']);
            }

            // Check if the user is logged in.
            $this->checkUser($app->request, $app->session);

            // Load the middlewares for the admin.
            require __DIR__ . '/web/middlewares.php';

            // Add the middlewares to the application.
            $app->addRouteMiddleware('checkLoggedUser');
            $app->addRouteMiddleware('checkNotLoggedUser');

            // Load the routes for the admin.
            foreach (require __DIR__ . '/web/routes.php' as $route) {
                $app->router->add(
                    ...array_merge(
                        $route,
                        ['path' => $this->prefix . $route['path']]
                    )
                );
            }
        }
    }

    /**
     * Renders a template with the given context.
     *
     * @param string $template The template file to render.
     * @param array $context The context data for the template.
     * @return response The rendered response.
     */
    public function template(string $template, array $context = []): response
    {
        $engine = new template(__DIR__);
        return application::$app->response->write(
            $engine->render($template, $context)
        );
    }

    /**
     * Retrieves all defined models.
     *
     * @return array An array of modelView instances.
     */
    public function getModels(): array
    {
        $models = [];
        foreach ($this->setup['models'] ?? [] as $model) {
            $models[] = is_string($model) ? new modelView($model) : $model;
        }
        return $models;
    }

    /**
     * Retrieves a specific model by ID.
     *
     * @param string $id The model identifier.
     * @return modelView The corresponding modelView instance.
     * @throws Exception if the model is not found.
     */
    public function getModel(string $id): modelView
    {
        foreach ($this->getModels() as $model) {
            if ($model->name() == $id) {
                return $model;
            }
        }

        throw new Exception("Model does not match with ({$id})");
    }

    /**
     * Retrieves a setup configuration value by key.
     *
     * @param string $key The setup key.
     * @param mixed $default The default value if the key is not found.
     * @return mixed The configuration value.
     */
    public function getSetup(string $key, $default = null): mixed
    {
        return $this->setup[$key] ?? $default;
    }

    /**
     * Enqueues a file for inclusion in the setup.
     *
     * @param string $type The type of file ('css' or 'js').
     * @param string $file The file to enqueue.
     * @return self
     */
    public function enque(string $type, string $file): self
    {
        $this->setup['enque'][$type][] = $file;
        return $this;
    }

    /**
     * Dequeues and returns concatenated content of enqueued files.
     *
     * @param string $type The type of file ('css' or 'js').
     * @return string The concatenated content of enqueued files.
     */
    public function deque(string $type): string
    {
        $scripts = '';
        foreach ($this->setup['enque'][$type] ?? [] as $script) {
            $scripts .= is_file($script) ? file_get_contents($script) : $script;
        }
        return $scripts;
    }

    /**
     * Checks the current user session and assigns the user setup.
     *
     * @param request $request The request instance.
     * @param session $session The session instance.
     * @return void
     */
    private function checkUser(request $request, session $session): void
    {
        if ($session->has('logged') && $session->get('logged')) {
            $request->user = $this->getSetup('user', ['name' => 'admin']);
        }
    }
}