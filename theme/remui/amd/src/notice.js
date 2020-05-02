define(['jquery'], function($) {
    var SELECTORS = {
        CONTAINER: '.edwiser-notice'
    };

    /**
     * Show custom notice
     * @param  {String} message Message to show in notice
     * @param  {String} type    Type of message [success|info|warning|danger]. Default success
     * @param  {Number} time    For how long notice will appear. Default 1500
     */
    var show = function(message, type, time) {
        // Reinitialize type and time if is not set
        type = type || 'success';
        time = time || 1500;

        // Create notice alert
        var notice = $('<div></div>');
        $(SELECTORS.CONTAINER).show().append(notice);
        notice.text(message)
        .addClass('alert alert-'+type)
        .css({
            width: 'fit-content',
            'margin-left': 'auto',
            'margin-right': 'auto'
        })
        // Show notice alert
        .fadeIn('slow');
        setTimeout(function() {
            notice.fadeOut('slow', function() {
                notice.remove();
                if ($(SELECTORS.CONTAINER).is(':empty')) {
                    $(SELECTORS.CONTAINER).hide();
                }
            });
        }, time);
    };

    /**
     * Show success notice
     * @param  {String} message Message to show in notice
     * @param  {Number} time    For how long notice will appear. Default 1500
     */
    var success = function(message, time) {
        time = time || 1500;
        show(message, 'success', time);
    };

    /**
     * Show info notice
     * @param  {String} message Message to show in notice
     * @param  {Number} time    For how long notice will appear. Default 1500
     */
    var info = function(message, time) {
        time = time || 1500;
        show(message, 'info', time);
    };

    return {
        success: success,
        info: info
    };
});