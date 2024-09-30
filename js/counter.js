(function($) {
    $.fn.countTo = function(options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // calculate how many times to update the value, and the increment per update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;

        return $(this).each(function() {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html( value.toFixed(options.decimals) + '+' );

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;
                    $(_this).html( value.toFixed(options.decimals)  + '+'  );

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);
                    }
                }
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0,
        to: 100,
        speed: 1000,
        refreshInterval: 100,
        decimals: 0,
        onUpdate: null,
        onComplete: null,
    };
})(jQuery);

jQuery(function($) {
    $('#counters').waypoint(function(direction) {
        $('.quantity-counter1').countTo({
            from: 0,
            to: 28,
            speed: 2000,
            refreshInterval: 50,
            onComplete: function(value) {
                console.debug(this);
            }
        });
        $('.quantity-counter2').countTo({
            from: 0,
            to: 25,
            speed: 2000,
            refreshInterval: 50,
            onComplete: function(value) {
                console.debug(this);
            }
        });
        $('.quantity-counter3').countTo({
            from: 0,
            to: 18000,
            speed: 2000,
            refreshInterval: 50,
            onComplete: function(value) {
                console.debug(this);
            }
        });
        $('.quantity-counter4').countTo({
            from: 0,
            to: 100,
            speed: 2000,
            refreshInterval: 50,
            onComplete: function(value) {
                console.debug(this);
            }
        });
    }, {offset:"100%", triggerOnce:true});
});
