<?php
namespace Peacock\Core\Interfaces;

interface ModuleInterface
{
    public function getName();

    public function initDefault();

    /**
     * @return string|null
     */
    public function getLoadHook();

    /**
     * @return string
     */
    public function getBootstrapHook();

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @return void
     */
    public function bootstrap();

    /**
     * @return void
     */
    public function execute();
}
