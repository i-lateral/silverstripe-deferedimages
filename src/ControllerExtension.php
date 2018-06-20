<?php

namespace ilateral\SilverStripe\DeferedImages;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;
use SilverStripe\Core\Config\Config;

class ControllerExtension extends Extension
{
    /**
     * should the javascript be added via requirements
     *
     * @config
     * @var boolean
     */
    private static $load_script = true;

    public function onAfterInit()
    {
        if (Config::inst()->get(static::class, 'load_script')) {
            Requirements::javascript('ilateral/silverstripe-deferedimages:client/dist/js/deferedimages.min.js');
        }
    }
}