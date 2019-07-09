# Silverstripe Defered Images Module

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/i-lateral/silverstripe-deferedimages/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/i-lateral/silverstripe-deferedimages/?branch=master)

Module that loads images as a very low res version first, then uses JavaScript to load the full version

Now implements [heyday/silverstripe-responsive-images](https://github.com/heyday/silverstripe-responsive-images) without any extra configuration. Simply configure the responsive images module as you normally would and call them in the template. 

## Installation

install via composer

``` 
composer require i-lateral/silverstripe-deferedimages
```
## Setup

By default this is added to the `PageController` if it's available. This can be added to extra controller via config.yml:

```
<YourController>:
  extensions:
    - ilateral\SilverStripe\DeferedImages\ControllerExtension
```

### Theme

This module has a small theme addon that needs to be added you your theme stack in config.yml to load it's template.

```
SilverStripe\View\SSViewer:
  themes:
    - 'custom' //your_theme
    - 'deferedimages'
    - '$default'
```

Alternatively, you can copy the template into your own theme.

## Configuration

By default all images called in the templates using the standard `$Image.Type()` call will be defered.

The temporary image can be customised in size, quality and appearance via config:

```
ilateral\SilverStripe\DeferedImages\DeferedImage:
    pixelate: 15 // Applies a pixelation effect to the current image with a given size of pixels.
    blur: 50 // Apply a gaussian blur filter with a optional amount on the current image. Use values between 0 and 100.
    quality: 10  // Define the quality of the encoded image. Data ranging from 0 to 100.
    scale: 100 // Will resize the image to a percentage amount. 100 = current size, 50 = half size, etc.
    limit: 1000 // sets a maximum width for the defered image - will force scaling if original image is larger than this.
```

To enable deferring of images added in a `HTMLEditorField` you will need to enable the included shortcode parser via _config.php:

```
<?php

use SilverStripe\View\Parsers\ShortcodeParser;
use ilateral\SilverStripe\DeferedImages\DeferedImageShortcodeProvider;

ShortcodeParser::get('default')
    ->register('image', [DeferedImageShortcodeProvider::class, 'handle_shortcode']);
```
