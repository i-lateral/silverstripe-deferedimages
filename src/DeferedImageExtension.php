<?php

namespace ilateral\SilverStripe\DeferedImages;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Assets\Image_Backend;
use SilverStripe\Core\Config\Configurable;

class DeferedImage extends DataExtension
{
    use Configurable;

    /**
     * pixelate level
     * 
     * @config
     * @var integer
     */
    private static $pixelate = 15;

    /**
     * Amount of blur to apply from 0 to 100
     * 
     * @config
     * @var integer
     */
    private static $blur = 0;

    /**
     * quality of new image from 0 to 100
     * 
     * @config
     * @var integer
     */
    private static $quality = 10;

    /**
     * scale of the new image as a percentage
     * 
     * @config
     * @var integer
     */
    private static $scale = 100;

    /**
     * maximum width of image before processing
     * 
     * @config
     * @var integer
     */
    private static $limit = 1400;

    /**
     * Generates a reduced quality version of the current image
     * @config
     * @return void
     */
    public function MicroImage()
    {
        $pixel = $this->config()->pixelate;
        $blur = $this->config()->blur;
        $quality = $this->config()->quality;
        $scale = $this->config()->scale;
        $limit = $this->config()->limit;
        
        $variant = $this->owner->variantName(__FUNCTION__, $pixel, $blur, $quality, $scale);
        return $this->owner->manipulateImage(
            $variant, 
            function (Image_Backend $backend) use ($pixel, $blur, $quality, $scale, $limit) {
                $width = $backend->getWidth();
                if ($width > 0 && $width > $limit && ($scale == 100 || $scale == null)) {
                    $scale = ($limit/$width)*100;
                }
                $clone = clone $backend;
                $resource = clone $backend->getImageResource();
                if ($scale != 100) {
                    $width = $backend->getWidth() * ($scale/100);
                    $height = $backend->getHeight() * ($scale/100);
                    $resource->resize($width, $height);
                }
                $resource->pixelate($pixel)->blur($blur)->encode('jpg', $quality);
                $clone->setImageResource($resource);
                return $clone;
            }
        );
    }
}
