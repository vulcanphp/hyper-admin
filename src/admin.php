<?php

namespace admin;

use hyper\response;
use hyper\template;
use hyper\application;
use admin\core\modelView;
use admin\core\drawer;
use hyper\request;
use hyper\session;
use Exception;

class admin
{
    public static admin $instance;
    private array $setup = [
        'enque' => [
            'css' => [__DIR__ . '/assets/app.css'],
            'js' => [__DIR__ . '/assets/app.js'],
        ],
    ];
    public string $prefix;
    public drawer $settings;

    public function __construct()
    {
        self::$instance = $this;
    }

    public function setup(application $app): void
    {
        $this->settings = new drawer(app_dir('settings.dr'));
        $this->prefix = $app->env['admin_prefix'];

        if (strpos($app->request->path, $this->prefix) === 0) {

            if (isset($app->env['admin'])) {
                $this->setup = array_merge($this->setup, require $app->env['admin']);
            }

            $this->checkUser($app->request, $app->session);

            require __DIR__ . '/web/middlewares.php';

            $app->addRouteMiddleware('checkLoggedUser');
            $app->addRouteMiddleware('checkNotLoggedUser');

            foreach (require __DIR__ . '/web/routes.php' as $route) {
                $route['path'] = $this->prefix . $route['path'];
                $app->router->add(...$route);
            }
        }
    }

    public function template(string $template, array $context = []): response
    {
        $engine = new template(__DIR__);
        return application::$app->response->write($engine->render($template, $context));
    }

    public function getModels(): array
    {
        $models = [];
        foreach ($this->setup['models'] ?? [] as $model) {
            $models[] = is_string($model) ? new modelView($model) : $model;
        }
        return $models;
    }

    public function getModel(string $id): modelView
    {
        foreach ($this->getModels() as $model) {
            if ($model->name() == $id) {
                return $model;
            }
        }

        throw new Exception("Model does not matched with ({$id})");
    }

    public function getSetup(string $key, $default = null): mixed
    {
        return $this->setup[$key] ?? $default;
    }

    public function enque(string $type, string $file): self
    {
        $this->setup['enque'][$type][] = $file;
        return $this;
    }

    public function deque(string $type): string
    {
        $scripts = '';
        foreach ($this->setup['enque'][$type] ?? [] as $script) {
            $scripts .= is_file($script) ? file_get_contents($script) : $script;
        }
        return $scripts;
    }

    private function checkUser(request $request, session $session): void
    {
        if ($session->has('logged') && $session->get('logged')) {
            $request->user = $this->getSetup('user', ['name' => 'admin']);
        }
    }
}
