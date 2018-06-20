# silverstripe-deferedimages
Module that loads images as a very low res version first, then uses JavaScript to load the full version

By default all images called in the templates using the standard `$Image.Type()` call will be defered.