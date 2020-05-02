define([
    'jquery',
    'core/custom_interaction_events',
    'core/notification',
    'block_remuiblck/events',
    'block_remuiblck/manage_courses_view'
], function(
    $,
    CustomEvents,
    Notification,
    RemuiblckEvents,
    ManageCoursesView
) {
    var SELECTORS = {
        COURSES_LIST: '[data-region="manage-courses-list"]',
        DISPLAY_FILTER: '[data-region="manage-courses-display-filter"]',
        FILTER_OPTION: '[data-value]',
        PER_PAGE_FILTER: '[data-region="per-page-filter"]',
        PAGINATE: '[data-action-paginate]',
        NEXT: '[data-next]',
        PREVIOUS: '[data-previous]',
    };

    /**
     * Event listener for the display selector ("Card", "List", "Summary").
     *
     * @param {object} root The root element for the manage courses block
     */
    var registerManageCourseDisplayFilter = function(root) {
        var manageCourseDisplayFilterContainer = $(root).find(SELECTORS.DISPLAY_FILTER);
        CustomEvents.define(manageCourseDisplayFilterContainer, [CustomEvents.events.activate]);
        manageCourseDisplayFilterContainer.on(
            CustomEvents.events.activate,
            SELECTORS.FILTER_OPTION,
            function(e, data) {
                data.originalEvent.preventDefault();

                var option = $(e.target).closest(SELECTORS.FILTER_OPTION);

                if (option.hasClass('active')) {
                    // If it's already active then we don't need to do anything.
                    return;
                }

                $(e.target).trigger(RemuiblckEvents.MANAGE_COURSES_DISPLAY_FILTER_CHANGE);
                M.util.set_user_preference('managecourseview', option.data('value'));
                ManageCoursesView.loadCourses(
                    root,
                    option.data('value'),
                    getManageCoursesPerPage(root),
                    $(root).find(SELECTORS.COURSES_LIST).data('currentpage')
                );
            }
        );
    };

    /**
     * Event listener for the per page selector
     *
     * @param {object} root The root element for the manage courses block
     */
    var registerManageCoursePerPageFilter = function(root) {
        var manageCoursePerPageFilterContainer = $(root).find(SELECTORS.PER_PAGE_FILTER);
        CustomEvents.define(manageCoursePerPageFilterContainer, [CustomEvents.events.activate]);
        manageCoursePerPageFilterContainer.on(
            CustomEvents.events.activate,
            SELECTORS.FILTER_OPTION,
            function(e, data) {
                data.originalEvent.preventDefault();

                var option = $(e.target).closest(SELECTORS.FILTER_OPTION);

                if (option.hasClass('active')) {
                    // If it's already active then we don't need to do anything.
                    return;
                }

                $(e.target).trigger(RemuiblckEvents.MANAGE_COURSES_PAGE_FILTER_CHANGE);
                M.util.set_user_preference('managecourseperpage', option.data('value'));
                $(root).find(SELECTORS.COURSES_LIST).data('currentpage', 1);
                ManageCoursesView.loadCourses(
                    root,
                    getManageCourseDisplay(root),
                    option.data('value'),
                    1
                );
            }
        );
    };

    /**
     * Event listener for the pagination controls
     *
     * @param {object} root The root element for the manage courses block
     */
    var registerManageCoursePageFilter = function(root) {
        var manageCoursePageFilterContainer = $(root).find(SELECTORS.PAGINATE);
        manageCoursePageFilterContainer.on(
            'click',
            function(e) {
                var pagenumber = $(root).find(SELECTORS.COURSES_LIST).data('currentpage');
                var maxpage = $(root).find(SELECTORS.COURSES_LIST).data('totalpages');
                if ($(this).is(SELECTORS.NEXT)) {
                    if (pagenumber == maxpage) {
                        return;
                    }
                    pagenumber++;
                } else if($(this).is(SELECTORS.PREVIOUS)) {
                    if (pagenumber == 1) {
                        return;
                    }
                    pagenumber--;
                } else {
                    return;
                }
                $(root).find(SELECTORS.COURSES_LIST).data('currentpage', pagenumber);
                $(e.target).trigger(RemuiblckEvents.MANAGE_COURSES_PAGINATE);
                ManageCoursesView.loadCourses(
                    root,
                    getManageCourseDisplay(root),
                    getManageCoursesPerPage(root),
                    pagenumber
                );
            }
        );
    };

    /**
     * Get manage courses display dropdown selection
     * @param  DOM    root block DOM object
     * @return string      selected duration option
     */
    var getManageCourseDisplay = function(root) {
        return $(root).find(SELECTORS.DISPLAY_FILTER).find(SELECTORS.FILTER_OPTION + '.active').data('value');
    }

    /**
     * Get manage courses filter dropdown selection
     * @param  DOM    root block DOM object
     * @return string      selected per page courses
     */
    var getManageCoursesPerPage = function(root) {
        return $(root).find(SELECTORS.PER_PAGE_FILTER).find(SELECTORS.FILTER_OPTION + '.active').data('value');
    }

    /**
     * Initialise manage courses filters events
     * @param DOM root block DOM object
     */
    var init = function(root) {
        registerManageCourseDisplayFilter(root);
        registerManageCoursePerPageFilter(root);
        registerManageCoursePageFilter(root);
        ManageCoursesView.loadCourses(root, getManageCourseDisplay(root), getManageCoursesPerPage(root));
    };
    return {
        init: init,
        getManageCourseDisplay: getManageCourseDisplay
    };
});
