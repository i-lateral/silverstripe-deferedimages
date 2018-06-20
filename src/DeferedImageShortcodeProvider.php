<?php

namespace ilateral\SilverStripe\DeferedImages;

use SilverStripe\Dev\Debug;
use SilverStripe\View\HTML;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Assets\Storage\AssetStore;
use SilverStripe\Assets\Shortcodes\ImageShortcodeProvider;

class DeferedImageShortcodeProvider extends ImageShortcodeProvider
{
    /**
     * Replace"[image id=n]" shortcode with an image reference.
     * Permission checks will be enforced by the file routing itself.
     *
     * @param array $args Arguments passed to the parser
     * @param string $content Raw shortcode
     * @param ShortcodeParser $parser Parser
     * @param string $shortcode Name of shortcode used to register this handler
     * @param array $extra Extra arguments
     * @return string Result of the handled shortcode
     */
    public static function handle_shortcode($args, $content, $parser, $shortcode, $extra = array())
    {
        $cache = static::getCache();
        $cacheKey = static::getCacheKey($args);

        $item = $cache->get($cacheKey);
        if ($item) {
            /** @var AssetStore $store */
            $store = Injector::inst()->get(AssetStore::class);
            if (!empty($item['filename'])) {
                $store->grant($item['filename'], $item['hash']);
            }
            return $item['markup'];
        }

        // Find appropriate record, with fallback for error handlers
        $record = static::find_shortcode_record($args, $errorCode);
        if ($errorCode) {
            $record = static::find_error_record($errorCode);
        }
        if (!$record) {
            return null; // There were no suitable matches at all.
        }

        // Check if a resize is required
        $src = $record->Link();
        $micro_url = false;
        if ($record instanceof Image) {
            $width = isset($args['width']) ? $args['width'] : null;
            $height = isset($args['height']) ? $args['height'] : null;
            $hasCustomDimensions = ($width && $height);
            if ($hasCustomDimensions && (($width != $record->getWidth()) || ($height != $record->getHeight()))) {
                $resized = $record->ResizedImage($width, $height);
                // Make sure that the resized image actually returns an image
                if (!empty($resized)) {
                    $src = $resized->getURL();
                }
            }
            $micro_url = $record->MicroImage()->getURL();
        }

        // Build the HTML tag
        $attrs = array_merge(
            // Set overrideable defaults
            ['src' => '', 'alt' => $record->Title],
            // Use all other shortcode arguments
            $args,
            // But enforce some values
            ['id' => '', 'src' => $src]
        );

        // Clean out any empty attributes
        $attrs = array_filter($attrs, function ($v) {
            return (bool)$v;
        });

        if ($micro_url) {
            $attrs['data-src'] = $src;
            $attrs['src'] = $micro_url;
        }

        $markup = HTML::createTag('img', $attrs);

        // cache it for future reference
        $cache->set($cacheKey, [
            'markup' => $markup,
            'filename' => $record instanceof File ? $record->getFilename() : null,
            'hash' => $record instanceof File ? $record->getHash() : null,
        ]);

        return $markup;
    }
}