<?php
namespace Peacock\Core\Interfaces\Resolver;

interface TemplateTagResolverInterface
{
    /**
     * @param string $template
     *
     * @return static
     */
    public function setTemplate($template);

    /**
     * @param string $title
     *
     * @return static
     */
    public function setTitle($title);

    /**
     * @param string $sep
     *
     * @return static
     */
    public function setSep($sep);

    /**
     * @param string $siteName
     *
     * @return static
     */
    public function setSiteName($siteName);

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getSep();

    /**
     * @return string
     */
    public function getSiteName();

    /**
     * @return string
     */
    public function resolve();
}
