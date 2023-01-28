<?php
namespace Peacock;

use Peacock\Core\ModuleManager;

final class Peacock
{
    /**
     * @var static
     */
    protected static $instance;

    /**
     * @var \Peacock\Core\ModuleManager
     */
    protected $moduleManager;

    protected function __construct()
    {
        $this->bootstrap();
        $this->initHooks();
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function bootstrap()
    {
        $this->moduleManager = ModuleManager::getInstance();
    }

    private function initHooks()
    {
        register_activation_hook(WP_PEACOCK_PLUGIN_FILE, [Install::class, 'active']);
        register_deactivation_hook(WP_PEACOCK_PLUGIN_FILE, [Install::class, 'deactive']);

        add_action('plugins_loaded', [$this->moduleManager, 'load'], 15);
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $name;
        }
    }
}
