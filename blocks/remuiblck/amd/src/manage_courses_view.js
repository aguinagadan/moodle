define([
    'jquery',
    'core/ajax',
    'core/notification',
    'core/templates',
    'theme_remui/slick',
    'theme_remui/jquery-toolbar'
], function(
    $,
    ajax,
    Notification,
    Templates,
    toolbar
) {
    // Declair all required selectors
    var SELECTORS = {
        COURSES_LIST: '[data-region="manage-courses-list"]',
        COURSES_LIST_DECK: '#manage_courses .card-deck.dashboard-card-deck',
        COURSE_MENU: '.coursemenubtn[data-region="managecourses"]',
        PAGES: '[data-region-pages]'
    };

    // Declair all required promises
    var PROMISES = {
        /**
         * Get list of courses which user is enrolled as teacher
         *
         * @param {String} type        Type of view selected card|list|summary
         * @param {Number} perpage     Number of courses per page
         * @param {Number} currentpage Current page number
         *
         * @return {promise} Ajax promise call
         */
        GET_MANAGE_COURSES_LIST: function(type, perpage, currentpage) {
            return ajax.call([{
                methodname: 'block_remuiblck_get_manage_courses_list',
                args: {
                    type: type,
                    perpage: perpage,
                    currentpage: currentpage
                }
            }])[0];
        }
    };

    // Declair all required templates
    var TEMPLATES = {
        MANAGE_COURSES_LIST: 'block_remuiblck/manage_courses_list_items'
    };

    /**
     * Get extra data required for course list item
     * @param  {array} courses courses array
     * @return {array}         courses array with extra data
     */
    var extraData = function(courses) {
        courses.enrollink = M.cfg.wwwroot + "/user/index.php?id=";
        courses.enroltitle = M.util.get_string('enrolusers', 'core_enrol');

        courses.graderreportlink = M.cfg.wwwroot + "/grade/report/grader/index.php?id=";
        courses.graderreporttitle = M.util.get_string('graderreport', 'core_grades');

        courses.activityreportlink = M.cfg.wwwroot + "/report/outline/index.php?id=";
        courses.activityreporttitle = M.util.get_string('activityreport', 'moodle');

        courses.editcourselink = M.cfg.wwwroot + "/course/edit.php?id=";
        courses.editcoursetitle = M.util.get_string('editcourse', 'theme_remui');

        courses.coursereporttitle = M.util.get_string('coursereport', 'moodle');

        courses.courseviewlink = M.cfg.wwwroot + "/course/view.php?id=";
        return courses;
    }

    /**
     * Apply slick styling to courses list if user selects card view
     * @param  {String} root Root element id
     */
    var applySlick = function(root) {
        var colors = ['#f39f45', '#f95e5f', '#2fb0bf', '#2fb786', '#526069', '#46657d'];
        $('#manage_courses .wdm-course-card-body').each(function(index, element) {
            index >= colors.length ? index = index % colors.length : index = index;
            $(element).css('background-color', colors[index]);
        });
        $('#manage_courses .dashboard-card-deck').css("overflow", "unset");
        $('#manage_courses .dashboard-card-deck').not('.slick-initialized').each(function(index, element) {
            $(element).slick({
                dots: false,
                arrows: true,
                infinite: false,
                rtl: ($("html").attr("dir") == "rtl") ? true : false,
                opacity: 0,
                speed: 500,
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                    }, {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                    }, {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                    }
                ]
            });
            $(window).trigger('resize');
        });
    };

    /**
     * Load courses in course list view
     * @param {String} root        Root element id
     * @param {String} type        Type of view selected card|list|summary
     * @param {Number} perpage     Number of courses per page
     * @param {Number} currentpage Current page number
     */
    var loadCourses = function(root) {

        var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'card';
        var perpage = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 5;
        var currentpage = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 1;


        PROMISES.GET_MANAGE_COURSES_LIST(type, perpage, currentpage).done(function(response) {
            if (response.courses.length != 0) {
                response = extraData(response);
                response[type] = true;
                $(root).find(SELECTORS.COURSES_LIST).data('currentpage', currentpage);
                $(root).find(SELECTORS.COURSES_LIST).data('totalcourses', response.totalcourses);
                $(root).find(SELECTORS.COURSES_LIST).data('totalpages', response.totalpages);
            }
            Templates.render(TEMPLATES.MANAGE_COURSES_LIST, response).done(function(html) {
                $(root).find(SELECTORS.COURSES_LIST).html(html).attr('data-type', type);
                $(root).find(SELECTORS.PAGES).text(M.util.get_string('showingfromto', 'block_remuiblck', {
                    start: response.start,
                    to: response.end,
                    total: response.totalcourses
                }));
                if (type == 'card') {
                    applySlick(root);
                }
                $(root).find(SELECTORS.COURSES_LIST).find(SELECTORS.COURSE_MENU).each(function(){
                    $(this).toolbar({
                        content: $(this).data('toolbar'),
                        position: 'bottom',
                        style: 'primary',
                        event: 'click',
                        hideOnClick: true
                    });
                    // fix redirect issue
                    $(this).on('toolbarItemClick', function (e, el) {
                        if (el.href) window.location.href = el.href;
                    });
                });
            }).fail(Notification.exception);
        }).fail(Notification.exception);
    };

    /**
     * Initialize events
     * @param  {String} root Root element id
     */
    var initialiseEvents = function(root) {
        // Nothing to do here for now
    };

    /**
     * Initialise manage courses view
     * @param  {String} root Root element id
     */
    var init = function(root) {
        initialiseEvents(root);
    };
    return {
        init: init,
        loadCourses: loadCourses
    };
});
