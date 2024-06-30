<?php

namespace admin\core;

use hyper\model;
use ReflectionClass;

class modelView
{
    public function __construct(
        private model|string $model,
        private ?string $name = null,
        private ?string $name_plural = null,
        private ?array $fields = null,
        private ?array $search = null,
        private ?array $filter = null,
        private null|string|array $with = null,
        private null|string|array $where = null,
        private ?string $order = null,
        private ?array $actions = null,
    ) {
    }

    public function name(): string
    {
        return $this->name ??= $this->reflection()->getShortName();
    }

    public function name_plural(): string
    {
        return $this->name_plural ??= $this->name();
    }

    private function reflection(): ReflectionClass
    {
        return new ReflectionClass($this->getModel());
    }

    public function getModel(): model
    {
        if (is_string($this->model)) {
            $this->model = new $this->model;
        }
        return $this->model;
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'has') === 0) {
            $name = strtolower(str_replace('has', '', $name));
            return $this->{$name} !== null;
        }

        $name = strtolower(str_replace('get', '', $name));
        return $this->{$name};
    }
}
