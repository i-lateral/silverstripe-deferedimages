(function(window) {
    // script thanks to: https://varvy.com/pagespeed/defer-images.html
    function imagedefer() {
        var imgDefer = document.getElementsByTagName('img');
        for (var i=0; i<imgDefer.length; i++) {
            if(imgDefer[i].getAttribute('data-src')) {
                imgDefer[i].setAttribute('src',imgDefer[i].getAttribute('data-src'));
            }
        }
    }
    
    if (window.addEventListener) {
        window.addEventListener('load', imagedefer, false);
    } else if (window.attachEvent) {
        // Microsoft
        window.attachEvent('onload', imagedefer);
    }
}(window));