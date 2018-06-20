# silverstripe-deferedimages
Module that loads images as a very low res version first, then uses JavaScript to load the full version

By default all images called in the templates using the standard `$Image.Type()` call will be defered.

The temporary image can be customised in size, quality and appearance via config:

```
ilateral\SilverStripe\DeferedImages\DeferedImage:
    pixelate: 15 // Applies a pixelation effect to the current image with a given size of pixels.
    blur: 50 // Apply a gaussian blur filter with a optional amount on the current image. Use values between 0 and 100.
    quality: 10  // Define the quality of the encoded image. Data ranging from 0 to 100.
    scale: 100 // Will resize the image to a percentage amount. 100 = current size, 50 = half size, etc.
```

To enable deferring of images added in a `HTMLEditorField` you will need to enable the included shortcode parser via _config.php:

```
<?php
use SilverStripe\View\Parsers\ShortcodeParser;
use ilateral\SilverStripe\DeferedImages\DeferedImageShortcodeProvider;

ShortcodeParser::get('default')
    ->register('image', [DeferedImageShortcodeProvider::class, 'handle_shortcode']);
```