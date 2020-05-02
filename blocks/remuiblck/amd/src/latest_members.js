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
    var TEMPLATES = {
        LATEST_MEMBERS_LIST: 'block_remuiblck/latestmembers_list'
    };
    var PROMISES = {
        /**
         * Get latest members promise call
         * @return {promise} ajax promise
         */
        GET_LATEST_MEMBERS_LIST: function() {
            return ajax.call([{
                methodname : 'block_remuiblck_get_latest_members_list',
                args: {}
            }])[0];
        }
    };

    /**
     * Load latest members list
     * @param {DOM}    root     block DOM object
     */
    var loadMembers = function(root) {
        PROMISES.GET_LATEST_MEMBERS_LIST().done(function(response) {
            var output = Templates.render(TEMPLATES.LATEST_MEMBERS_LIST, response);
            output.done(function(html) {
                $(root).html(html);
            }).fail(Notification.exception);
        }).fail(Notification.exception);
    };

    /**
     * Load latest member list on initialisation
     * @param {DOM} root block DOM object
     */
    var init = function(root) {
        $(document).ready(function() {
            loadMembers(root);
        });
    };
    return {
        init: init
    };
});