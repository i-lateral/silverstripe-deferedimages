# silverstripe-deferedimages
Module that loads images as a very low res version first, then uses JavaScript to load the full version

By default all images called in the templates using the standard `$Image.Type()` call will be defered.

The temporary image can be customised in size, quality and appearance via config:

```
i-lateral\SilverStripe\DeferedImages\DeferedImage:
    pixelate: 15 // Applies a pixelation effect to the current image with a given size of pixels.
    blur: 50 // Apply a gaussian blur filter with a optional amount on the current image. Use values between 0 and 100.
    quality: 10  // Define the quality of the encoded image. Data ranging from 0 to 100.
    scale: 100 // Will resize the image by a percentage amount.
```
