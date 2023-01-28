<?php
namespace Peacock\Modules;

use Peacock\Core\Abstracts\SingletonModule;
use Peacock\Core\Interfaces\FrontendModuleInterface;
use Peacock\Core\Resolver\TemplateTagResolver;
use WP_Post;

class TitleModule extends SingletonModule implements FrontendModuleInterface
{
    public function getName()
    {
        return 'title';
    }

    public function loadHook()
    {
        return 'wp';
    }

    public function generateTitleTag($queried_object, $siteName, $sep)
    {
        $objectTitle = '';
        if (is_null($queried_object)) {
            $objectTitle = get_bloginfo('description');
        } else {
            if ($queried_object instanceof WP_Post) {
                $objectTitle = $queried_object->post_title;
            }
        }

        if ($objectTitle) {
            $titleTemplate = get_option('wp_peacock_title_template', '{% title %} {% sep %} {% site_name %}');
            $titleResolver = new TemplateTagResolver(
                apply_filters('wp_peacock_title_template', $titleTemplate),
                $objectTitle,
                $sep,
                $siteName
            );

            do_action_ref_array('wp_peacock_setup_title', [
                &$titleResolver,
                $queried_object,
                $siteName,
                $sep
            ]);

            return $titleResolver->resolve();
        }
    }

    public function filterTitleTag($title, $sep, $seplocation)
    {
        $queried_object = get_queried_object();
        $siteName       = get_bloginfo('name');

        $newTitleTag = $this->generateTitleTag($queried_object, $siteName, $sep, $seplocation);
        if (!empty($newTitleTag)) {
            return $newTitleTag;
        }

        return $title;
    }

    public function execute()
    {
        add_filter('wp_title', [$this, 'filterTitleTag'], 10, 3);
    }
}
