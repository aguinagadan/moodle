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
        FORUM_CONTAINER: '#recent_active_forum',
    };
    var TEMPLATES = {
        RECENT_FORUM_LIST: 'block_remuiblck/recent_active_forum_table'
    };
    var PROMISES = {
        /**
         * Get recent forum promise call
         * @return promise         ajax promise
         */
        GET_RECENT_FORUM: function() {
            return ajax.call([{
                methodname : 'block_remuiblck_get_recent_active_forum',
                args: {}
            }])[0];
        }
    };

    /**
     * Load recent feedback list
     * @param DOM    root     block DOM object
     */
    var loadRecentForum = function(root) {
        PROMISES.GET_RECENT_FORUM().done(function(response) {
            var output = Templates.render(TEMPLATES.RECENT_FORUM_LIST, response);
            output.done(function(html) {
                $(SELECTORS.FORUM_CONTAINER).html(html);
            }).fail(Notification.exception);
        }).fail(Notification.exception);
    };

    /**
     * Load recent forum on initialisation
     * @param DOM root block DOM object
     */
    var init = function(root) {
        $(document).ready(function() {
            loadRecentForum(root);
        });
    };
    return {
        init: init
    };
});
