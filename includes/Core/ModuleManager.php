<?php
namespace Peacock\Core;

use Peacock\Core\Abstracts\SingletonModule;
use Peacock\Core\Interfaces\AjaxModuleInterface;
use Peacock\Core\Interfaces\BackendModuleInterface;
use Peacock\Core\Interfaces\CronModuleInterface;
use Peacock\Core\Interfaces\FrontendModuleInterface;
use Peacock\Core\Interfaces\ModuleInterface;
use Peacock\Core\Interfaces\RestModuleInterface;
use Peacock\Core\Interfaces\SingletonModuleInterface;
use Peacock\Modules\CanonicalModule;
use Peacock\Modules\TitleModule;

class ModuleManager
{
    protected static $instance;

    /**
     * @var \Peacock\Core\Interfaces\ModuleInterface[]
     */
    protected $modules = [];

    /**
     * @var string[]
     */
    protected $moduleClasses = [];

    /**
     * @var boolean
     */
    private static $createdModules = false;

    protected function __construct()
    {
        $this->registerDefaultModules();
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function registerDefaultModules()
    {
        $this->register(TitleModule::class);
        $this->register(CanonicalModule::class);
    }

    /**
     * @param string|\Peacock\Core\Interfaces\ModuleInterface $module
     */
    public function register($module)
    {
        if ($module instanceof ModuleInterface) {
            $this->modules[$module->getName()] = $module;
        } elseif (is_string($module) && is_a($module, ModuleInterface::class, true)) {
            array_push($this->moduleClasses, $module);
        }
    }

    /**
     * @return boolean
     */
    private function isPassModuleCondition($module)
    {
        if (is_admin() && is_a($module, BackendModuleInterface::class, true)) {
            return true;
        } elseif (defined('DOING_AJAX') && is_a($module, AjaxModuleInterface::class, true)) {
            return true;
        } elseif (defined('DOING_CRON') && is_a($module, CronModuleInterface::class, true)) {
            return true;
        } elseif (defined('REST_REQUEST') && is_a($module, RestModuleInterface::class, true)) {
            return true;
        } elseif (is_a($module, FrontendModuleInterface::class, true)) {
            return true;
        }
        return false;
    }

    /**
     * @return \Peacock\Core\Interfaces\ModuleInterface|null
     */
    private function createObject($moduleClass)
    {
        if ($this->isPassModuleCondition($moduleClass)) {
            if (is_a($moduleClass, SingletonModule::class, true)) {
                return $moduleClass::getInstance();
            }
            /**
             * @var \Peacock\Core\Interfaces\ModuleInterface
             */
            $module = new $moduleClass();
            $module->initDefault();

            return $module;
        }
    }


    /**
     * @param \Peacock\Core\Interfaces\ModuleInterface $module
     */
    private function loadModule($module)
    {
        if ($this->isPassModuleCondition($module)) {
            $loadHook = $module->getLoadHook();

            $bootstrapHook  = $module->getBootstrapHook();
            if (did_action($bootstrapHook)) {
                // Bootstrap module immediately
                $module->bootstrap();
            } else {
                add_action($bootstrapHook, [$module, 'bootstrap']);
            }

            // Load module immediately
            if (empty($loadHook) || did_action($loadHook)) {
                $module->execute();
                return;
            }
            add_action($loadHook, [$module, 'execute']);
        }
    }

    /**
     * @return void
     */
    private function createModuleObjects()
    {
        foreach ($this->moduleClasses as $moduleClass) {
            $module = $this->createObject($moduleClass);
            if (is_null($module)) {
                continue;
            }

            if ($module instanceof SingletonModuleInterface) {
                $this->modules[$module->getName()] = $module;
            } else {
                array_push($this->modules, $module);
            }
        }
    }


    /**
     * @return void
     */
    public function load()
    {
        if (!static::$createdModules) {
            // Create module
            $this->createModuleObjects();

            static::$createdModules = true;
        }

        foreach (array_values($this->modules) as $module) {
            $this->loadModule($module);
        }
    }
}
