define([
    'jquery',
    'core/custom_interaction_events',
    'core/notification',
    'block_remuiblck/events',
    'block_remuiblck/task_view'
], function(
    $,
    CustomEvents,
    Notification,
    RemuiblckEvents,
    TaskView
) {
    var SELECTORS = {
        TASK_DURATION_FILTER: '[data-region="task-duration-filter"]',
        TASK_STATUS_FILTER: '[data-region="task-status-filter"]',
        TASK_SEARCH_FILTER: '[data-region="task-search-filter"]',
        TASK_CANCEL_SEARCH: '[data-region="cancel-result"]',
        TASK_FILTER_OPTION: '[data-value]'
    };

    /**
     * Event listener for the day selector ("Next 7 days", "Next 30 days", etc).
     *
     * @param {object} root The root element for the timeline block
     */
    var registerTaskDurationFilter = function(root) {
        var taskDurationFilterContainer = $(root).find(SELECTORS.TASK_DURATION_FILTER);
        CustomEvents.define(taskDurationFilterContainer, [CustomEvents.events.activate]);
        taskDurationFilterContainer.on(
            CustomEvents.events.activate,
            SELECTORS.TASK_FILTER_OPTION,
            function(e, data) {

                var option = $(e.target).closest(SELECTORS.TASK_FILTER_OPTION);

                if (option.hasClass('active')) {
                    // If it's already active then we don't need to do anything.
                    data.originalEvent.preventDefault();
                    return;
                }

                $(e.target).trigger(RemuiblckEvents.TASK_DURATION_FILTER_CHANGE);

                data.originalEvent.preventDefault();

                TaskView.loadTasks(root, option.data('value'), getTaskStatus(root), getTaskSearch(root));
            }
        );
    };

    /**
     * Get task duration dropdown selection
     * @param  DOM    root block DOM object
     * @return string      selected duration option
     */
    var getTaskDuration = function(root) {
        return $(root).find(SELECTORS.TASK_DURATION_FILTER).find(SELECTORS.TASK_FILTER_OPTION + '.active').data('value');
    }

    /**
     * Event listener for the day selector ("Next 7 days", "Next 30 days", etc).
     *
     * @param {object} root The root element for the timeline block
     */
    var registerTaskStatusFilter = function(root) {
        var taskStatusFilterContainer = $(root).find(SELECTORS.TASK_STATUS_FILTER);
        CustomEvents.define(taskStatusFilterContainer, [CustomEvents.events.activate]);
        taskStatusFilterContainer.on(
            CustomEvents.events.activate,
            SELECTORS.TASK_FILTER_OPTION,
            function(e, data) {

                var option = $(e.target).closest(SELECTORS.TASK_FILTER_OPTION);

                if (option.hasClass('active')) {
                    // If it's already active then we don't need to do anything.
                    data.originalEvent.preventDefault();
                    return;
                }

                $(e.target).trigger(RemuiblckEvents.TASK_STATUS_FILTER_CHANGE);

                data.originalEvent.preventDefault();

                TaskView.loadTasks(root, getTaskDuration(root), option.data('value'), getTaskSearch(root));
            }
        );
    };

    /**
     * Get task status dropdown selection
     * @param DOM     root block DOM object
     * @return string      selected status option
     */
    var getTaskStatus = function(root) {
        return $(root).find(SELECTORS.TASK_STATUS_FILTER).find(SELECTORS.TASK_FILTER_OPTION + '.active').data('value');
    }

    /**
     * Event listener for the search input
     *
     * @param {object} root The root element for the timeline block
     */
    var registerTaskSearchFilter = function(root) {
        var taskSearchFilterContainer = $(root).find(SELECTORS.TASK_SEARCH_FILTER);
        taskSearchFilterContainer.on(
            RemuiblckEvents.TASK_SEARCH_FILTER_CHANGE,
            function(e, data) {
                TaskView.loadTasks(root, getTaskDuration(root), getTaskStatus(root), $(this).val());
            }
        );
    };

    /**
     * Get task search input
     * @param DOM     root block DOM object
     * @return string      search query
     */
    var getTaskSearch = function(root) {
        return $(root).find(SELECTORS.TASK_SEARCH_FILTER).val();
    }

    /**
     * Initialise task filters events
     * @param DOM root block DOM object
     */
    var init = function(root) {
        registerTaskDurationFilter(root);
        registerTaskStatusFilter(root);
        registerTaskSearchFilter(root);
        $('body').on('click', SELECTORS.TASK_CANCEL_SEARCH, function(e) {
            e.preventDefault();
            $(root).find(SELECTORS.TASK_SEARCH_FILTER).val('').trigger('input');
            return;
        });
    };
    return {
        init: init,
        getTaskDuration: getTaskDuration,
        getTaskStatus: getTaskStatus
    };
});
