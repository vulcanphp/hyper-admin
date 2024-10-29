<?php

namespace admin\core;

use hyper\model;
use ReflectionClass;

/**
 * Class modelView
 * 
 * This class is used to define the options of a model in the admin panel.
 * 
 * @package admin
 * @author Shahin Moyshan <Shahin.moyshan2@gmail.com>
 * @since 1.0.0
 */
class modelView
{
    /**
     * Model View constructor
     * 
     * @param model|string $model The model instance or model class name
     * @param string|null $name The name of the model
     * @param string|null $name_plural The plural name of the model
     * @param array|null $fields The fields of the model
     * @param array|null $search The search fields of the model
     * @param array|null $filter The filters of the model
     * @param null|string|array $with The relations of the model
     * @param null|string|array $where The conditions of the model
     * @param string|null $order The order of the model
     * @param array|null $actions The actions of the model
     */
    public function __construct(
        /**
         * The model instance or model class name
         * 
         * @var model|string
         */
        private model|string $model,

        /**
         * The name of the model
         * 
         * If not set, the name of the model will be the short name of the class
         * 
         * @var string|null
         */
        private ?string $name = null,

        /**
         * The plural name of the model
         * 
         * If not set, the plural name of the model will be the same as the name
         * 
         * @var string|null
         */
        private ?string $name_plural = null,

        /**
         * The fields of the model
         * 
         * If not set, all fields of the model will be displayed
         * 
         * @var array|null
         */
        private ?array $fields = null,

        /**
         * The search fields of the model
         * 
         * If not set, all fields of the model will be searchable
         * 
         * @var array|null
         */
        private ?array $search = null,

        /**
         * The filters of the model
         * 
         * If not set, no filter will be available
         * 
         * @var array|null
         */
        private ?array $filter = null,

        /**
         * The relations of the model
         * 
         * If not set, no relation will be loaded
         * 
         * @var null|string|array
         */
        private null|string|array $with = null,

        /**
         * The conditions of the model
         * 
         * If not set, no condition will be applied
         * 
         * @var null|string|array
         */
        private null|string|array $where = null,

        /**
         * The order of the model
         * 
         * If not set, the order will be the same as the table of the model
         * 
         * @var string|null
         */
        private ?string $order = null,

        /**
         * The actions of the model
         * 
         * If not set, the default actions will be used
         * 
         * @var array|null
         */
        private ?array $actions = null,
    ) {
    }

    /**
     * Get the name of the model
     * 
     * If the name is not set, the name of the model will be the short name of the class
     * 
     * @return string
     */
    public function name(): string
    {
        return $this->name ??= $this->reflection()->getShortName();
    }

    /**
     * Get the plural name of the model
     * 
     * If the plural name is not set, the plural name of the model will be the same as the name
     * 
     * @return string
     */
    public function name_plural(): string
    {
        return $this->name_plural ??= $this->name();
    }

    /**
     * Get the reflection of the model class
     * 
     * @return ReflectionClass
     */
    private function reflection(): ReflectionClass
    {
        return new ReflectionClass($this->getModel());
    }

    /**
     * Get the model instance
     * 
     * If the model is a string, it will be instantiated
     * 
     * @return model
     */
    public function getModel(): model
    {
        if (is_string($this->model)) {
            $this->model = new $this->model;
        }
        return $this->model;
    }

    /**
     * Dynamic call to the properties of the model
     * 
     * If the method starts with 'has', it will be considered as a getter
     * If the method starts with 'get', it will be considered as a getter
     * 
     * @param string $name
     * @param array $arguments
     * 
     * @return mixed
     */
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

