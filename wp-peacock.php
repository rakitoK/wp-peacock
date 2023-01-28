<?php
/**
 * Plugin Name: WP Peacock - another WordPress SEO plugin
 * Plugin description: Highlight your site in search results of Search Engines
 * Author: Puleeno nguyen
 * Author URI: https://puleeno.com
 * Tag: SEO
 */

use Peacock\Peacock;

define('WP_PEACOCK_PLUGIN_FILE', __FILE__);

class WP_Peacock
{
    protected $isReady = false;

    public function bootstrap()
    {
        $composerAutoloader = sprintf('%s/vendor/autoload.php', dirname(__FILE__));
        if (file_exists($composerAutoloader)) {
            require_once $composerAutoloader;
            $this->isReady = true;
        }
    }

    public function load()
    {
        if (empty($this->isReady)) {
            return;
        }

        // Load features
        Peacock::getInstance();
    }
}


$peakcock = new WP_Peacock();
$peakcock->bootstrap();
$peakcock->load();
