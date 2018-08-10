<?php
/**
 * DigestUrl plugin for Craft CMS 3.x
 *
 * Digests URLs
 *
 * @link      https://byteq.com
 * @copyright Copyright (c) 2018 byteq
 */

namespace byteq\digesturl;

use byteq\digesturl\twigextensions\DigestUrlTwigExtension;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

/**
 * Class DigestUrl
 *
 * @author    byteq
 * @package   DigestUrl
 * @since     2.0.0
 *
 */
class DigestUrl extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var DigestUrl
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '2.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new DigestUrlTwigExtension());

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'digest-url',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
