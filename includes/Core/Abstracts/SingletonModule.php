<?php
namespace Peacock\Core\Abstracts;

use Peacock\Core\Interfaces\SingletonModuleInterface;

abstract class SingletonModule extends ModuleAbstract implements SingletonModuleInterface
{
    protected static $instance;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
