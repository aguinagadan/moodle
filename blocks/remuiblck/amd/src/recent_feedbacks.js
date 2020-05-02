define([
    'jquery',
    'core/ajax',
    'core/notification',
    'core/templates'
], function(
    $,
    ajax,
    Notification,
    Templates
) {
    var SELECTORS = {
        FEEDBACKS_CONTAINER: '#recent_assignments',
    };
    var TEMPLATES = {
        RECENT_FEEDBACK_LIST: 'block_remuiblck/recent_assignments_list'
    };
    var PROMISES = {
        /**
         * Get recent feedbacks promise call
         * @return promise         ajax promise
         */
        GET_RECENT_FEEDBACKS: function() {
            return ajax.call([{
                methodname : 'block_remuiblck_get_recent_feedbacks',
                args: {}
            }])[0];
        }
    };

    /**
     * Load recent feedback list
     * @param DOM    root     block DOM object
     */
    var loadRecentFeedbacks = function(root) {
        PROMISES.GET_RECENT_FEEDBACKS().done(function(response) {
            var output = Templates.render(TEMPLATES.RECENT_FEEDBACK_LIST, response);
            output.done(function(html) {
                $(SELECTORS.FEEDBACKS_CONTAINER).html(html);
            }).fail(Notification.exception);
        }).fail(Notification.exception);
    };

    /**
     * Load recent feedbacks on initialisation
     * @param DOM root block DOM object
     */
    var init = function(root) {
        $(document).ready(function() {
            loadRecentFeedbacks(root);
        });
    };
    return {
        init: init
    };
});
