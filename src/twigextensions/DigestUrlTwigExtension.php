<?php
/**
 * DigestUrl plugin for Craft CMS 3.x
 *
 * Digests URLs
 *
 * @link      https://byteq.com
 * @copyright Copyright (c) 2018 byteq
 */

namespace byteq\digesturl\twigextensions;

use byteq\digesturl\DigestUrl;

use Craft;

/**
 * @author    byteq
 * @package   DigestUrl
 * @since     2.0.0
 */
class DigestUrlTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'DigestUrl';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('getDigestUrl', [$this, 'getDigestUrl']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getDigestUrl', [$this, 'getDigestUrl']),
        ];
    }

    public function getDigestUrl($asset, $urlParams=null)
    {
        $url = "";
        $timestamp = null;
        if (is_object($asset) && $asset instanceof \Craft\elements\Asset)
        {
            $this->_includeElementInTemplateCaches($asset);

            $timestamp = $asset->dateUpdated->getTimestamp();

            if($urlParams != null && strtolower($asset->getExtension()) != "svg")
            {
                if(\Craft::$app->plugins->isPluginInstalled('imager')) {
                  $url = \aelvan\imager\Imager::getInstance()->imager->transformImage($asset, $urlParams, null, null)->getUrl();
                }
                else {
                  $url = $asset->getUrl($urlParams);
                }
            }
            else
            {
                $url = $asset->getUrl();
            }
        }

        if($timestamp != null) {
            return preg_replace('/(\/[^\/]+)\.([^.\/]+)$/', ("$1-" . $timestamp . ".$2"), $url);
        }
        else {
            return $url;
        }
    }


    // BORROWED FROM https://github.com/craftcms/cms/blob/279116ba4091edc67c92fcbbefc257bb4fe30335/src/helpers/Template.php#L169

    /**
     * Includes an element in any active template caches.
     *
     * @param ElementInterface $element
     */
    private static function _includeElementInTemplateCaches(\Craft\elements\Asset $element)
    {
        /** @var Element $element */
        $elementId = $element->id;
        // Don't initialize the TemplateCaches service if we don't have to
        if ($elementId && Craft::$app->has('templateCaches', true)) {
            Craft::$app->getTemplateCaches()->includeElementInTemplateCaches($elementId);
        }
    }
}
