<?php
namespace Peacock\Core\Resolver;

use Peacock\Core\Abstracts\Resolver\TemplateTagResolverAbstract;

class TemplateTagResolver extends TemplateTagResolverAbstract
{
    public function resolve()
    {
        $tokens = [];
        $replaces = [];

        if (preg_match('/\{\%\s{1,}?([^%]+)\s{1,}?\%\}/', $this->getTemplate(), $matches)) {
            var_dump($matches);
            die;
        }

        return str_replace($tokens, $replaces, $this->getTemplate());
    }
}
