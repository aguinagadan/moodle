define([
    'jquery',
    'core/ajax',
    'core/notification',
    'core/templates',
    'core/modal_factory',
    'core/modal_events',
    'core/fragment',
    'block_remuiblck/modal_task_popup',
    'block_remuiblck/events'
], function(
    $,
    ajax,
    Notification,
    Templates,
    ModalFactory,
    ModalEvents,
    Fragment,
    ModalTaskPopup,
    RemuiblckEvents
) {
    var SELECTORS = {
        TASK_CONTAINER: '[data-region="task-list"]',
        PANEL: '.panel',
        TASK_PROCESSING: '.task-processing'
    };
    var TEMPLATES = {
        TASK_LIST_ITEMS: 'block_remuiblck/task-list-items'
    };
    var PROMISES = {
        /**
         * Get user tasks promise call
         * @param  string duration duration selection
         * @param  string status   status selection
         * @return promise         ajax promise
         */
        GET_USER_TASKS: function(duration, status, search) {
            return ajax.call([{
                methodname : 'block_remuiblck_get_user_tasks',
                args: {
                    duration: duration,
                    status: status,
                    search: search
                }
            }])[0];
        }
    };

    /**
     * Load task in the tasks list
     * @param DOM    root     block DOM object
     * @param String duration duration selection
     * @param String status   status selection
     * @param String search   search query
     */
    var loadTasks = function(root) {

        var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'today';
        var status = arguments.length > 1 && arguments[2] !== undefined ? arguments[2] : 'all';
        var search = arguments.length > 1 && arguments[3] !== undefined ? arguments[3] : '';

        PROMISES.GET_USER_TASKS(duration, status, search).done(function(response) {
            if (response.tasks.length == 0) {
                response.no_tasks_image = M.util.image_url('empty_task_list', 'block_remuiblck');
            }
            var output = Templates.render(TEMPLATES.TASK_LIST_ITEMS, response);
            output.done(function(html) {
                $(root).find(SELECTORS.TASK_CONTAINER).html(html);
                toggleTaskProcessing(root);
            }).fail(function(ex) {
                Notification.exception(ex);
                toggleTaskProcessing(root);
            });
        }).fail(function(ex) {
            Notification.exception(ex);
            toggleTaskProcessing(root);
        });
    };

    /**
     * Load task on initialisation
     * @param DOM root block DOM object
     */
    var init = function(root) {
        loadTasks(root);
    };

    /**
     * Toggle processing overlay to show that something happening in the background
     * @param  {string}  root root element id
     * @param  {Boolean} show true if wanna show overlay
     */
    var toggleTaskProcessing = function(root) {
        var show = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
        $(root).parents(SELECTORS.PANEL).find(SELECTORS.TASK_PROCESSING).toggleClass('show', show);
    }
    return {
        init: init,
        loadTasks: loadTasks,
        toggleTaskProcessing: toggleTaskProcessing
    };
});
