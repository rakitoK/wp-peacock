<?php
namespace Peacock\Core\Abstracts;

use Peacock\Core\Interfaces\ModuleInterface;

abstract class ModuleAbstract implements ModuleInterface
{
    public function initDefault()
    {
        // default object properties can be init at this method
    }

    /**
     * @return string|null
     */
    public function getLoadHook()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getBootstrapHook()
    {
        return 'after_setup_theme';
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * @return void
     */
    public function bootstrap()
    {
        // bootstrap module after theme loaded
    }
}
