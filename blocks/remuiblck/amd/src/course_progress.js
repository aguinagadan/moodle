define([
    'jquery',
    'core/ajax',
    'core/notification',
    'core/templates',
    'block_remuiblck/jquery.dataTables',
    'block_remuiblck/dataTables.bootstrap4',
    'theme_remui/jquery-asPieProgress',
    'theme_remui/aspieprogress'
], function(
    $,
    Ajax,
    Notification,
    Templates
) {
    var SELECTORS = {
        ROOT: '',
        TABLE: '#DataTables_Teacher',
        DATA_TABLE: '#DataTables_Teacher_wrapper',
        STUDENT_PROGRESS_ELEMENT: '.student_progress_ele',
        STUDENT_PROGRESS_TABLE: '#wdmCourseProgressTable',
        COURSE_NAME: '.wdm_course_name.has-student',
        MESSAGE_HIDDEN: '#messageidhidden',
        MESSAGE_AREA: '#messagearea',
        TOGGLE_DESCRIPTION: '.toggle-desc',
        REVERT: '#courserevertbtn',
        CUSTOM_MESSAGE: '.custom-message',
        MESSAGE_SEND: '.send-message',
        BLOCK_PROCESSING: '.block-processing'
    };

    var PROMISES = {
        /**
         * Get courses using ajax
         * @param  {String}  search Search query
         * @param  {Number}  length Number of courses
         * @param  {Number}  start  Start index of courses
         * @param  {Array}   order  Sorting order
         * @return {Promise}        Ajax promise
         */
        GET_COURSES: function(search, length, start, order) {
            return Ajax.call([{
                methodname: 'block_remuiblck_get_course_progress_list',
                args: {
                    search: search,
                    length: length,
                    start: start,
                    order: order
                }
            }])[0];
        },
        /**
         * Get course progress using course id and ajax
         * @param  {Number}  courseid Course id
         * @return {Promise}          Ajax promise
         */
        GET_COURSE_PROGRESS: function(courseid) {
            return Ajax.call([{
                methodname: 'block_remuiblck_get_course_progress',
                args: {
                    courseid: courseid
                }
            }])[0];
        },
        /**
         * Send message to student using student id and ajax
         * @param  {Number} studentid   Student id
         * @param  {String} messagetext Message text
         * @return {Promise}            Ajax promise
         */
        SEND_MESSAGE: function(studentid, messagetext) {
            return Ajax.call([{
                methodname: 'block_remuiblck_send_message',
                args: {
                    studentid: studentid,
                    messagetext: messagetext
                }
            }])[0];
        }
    }

    /**
     * Generate teacher courses table data from ajax response
     * @param  {Array}  courses Courses list with course details
     * @return {Object}         Data object
     */
    function generate_courses_table_data(courses) {
        var data = [];
        courses.forEach(function(course) {
            var dData = {};
            dData.index = '<div class="w-50" tabindex="0">'+course.index+'</div>';
            if (course.enrolledStudents > 0) {
                dData.course = '<div class="wdm_course_name has-student" data-courseid="'+course.id+'"><a href="javascript:void(0)">'+course.fullname+'</a></div>';
            } else {
                dData.course = '<div class="wdm_course_name" data-courseid="'+course.id+'" >'+course.fullname+'</div>';
            }
            dData.startdate = course.startdate;
            dData.students = '<div class="w-100"><span class="w-full pl-40">'+course.enrolledStudents+'</span></div>';
            dData.progress = '<td class="w-100 px-10"><div class="pie-progress pie-progress-xs m-0 w-35" data-plugin="pieProgress" data-valuemax="50" data-barcolor="#11c26d" data-size="20" data-barsize="3" data-goal="35" aria-valuenow="'+course.percentage+'" role="progressbar" style="max-width: 35px!important;"><div class="pie-progress-content" style="z-index:2;"> </div> <span class=" progress-percent" style="margin-left: 50px;position: absolute;top: 8px;">'+course.percentage+'%</span> </div></td>';
            data.push(dData);
        });
        return data;
    }

    //*****************
    // This is code is for table creation on dashboard
    // this code also toggles between course progress and student progress table
    // Function createDatatable() creates course progress table
    function createDatatable(root) {
        $(root).show().find(SELECTORS.TABLE).DataTable({
            "paging":   true,
            "pagingType": "simple_numbers",
            "autoWidth": true,
            "scrollX": true,
            "bPaginate": true,
            "bServerSide": true,
            language: {
                searchPlaceholder: M.util.get_string('search', 'moodle'),
                emptyTable: M.util.get_string('nomatchingcourses', 'core_backup'),
                lengthMenu: M.util.get_string('show', 'moodle') + " _MENU_ " + M.util.get_string('entries', 'moodle'),
                paginate: {
                    first:      M.util.get_string('first', 'moodle'),
                    previous:   M.util.get_string('previous', 'moodle'),
                    next:       M.util.get_string('next', 'moodle'),
                    last:       M.util.get_string('last', 'moodle')
                },
            },
            "ajax": function(data, callback, settings) {
                $(root).siblings(SELECTORS.BLOCK_PROCESSING).addClass('show');
                PROMISES.GET_COURSES(
                    data.search.value,
                    data.length,
                    data.start,
                    data.order[0]
                ).done(function(response) {
                    if (response.recordsTotal == 0) {
                        response.data = [];
                        callback(response);
                        $(root).siblings(SELECTORS.BLOCK_PROCESSING).removeClass('show');
                        return;
                    }
                    response.data = generate_courses_table_data(response.courses);
                    callback(response);
                    $(root).siblings(SELECTORS.BLOCK_PROCESSING).removeClass('show');
                }).fail(Notification.exception);
            },
            columns: [
                { data: "index" },
                { data: "course" },
                { data: "startdate" },
                { data: "students", "orderable" : false },
                { data: "progress", "orderable" : false }
            ],
            responsive: true,
            drawCallback: function(settings) {
                createPieProgress('');
            }
        });
    }


    /**
     * Create pie progress where div with .pie-progress class is present
     */
    function createPieProgress(target) {
        var element = $(SELECTORS.ROOT);
        if (target != '') {
            element = element.find(target);
        }
        element.find('.pie-progress').asPieProgress({
            namespace: 'pie-progress',
            speed: 30,
            classes: {
                svg: 'pie-progress-svg',
                element: 'pie-progress',
                number: 'pie-progress-number',
                content: 'pie-progress-content'
            }
        });
    }

    var courseProgressTable;
    /**
     * Fetch students course progress data using ajax and display in table format
     * @param  {int} courseid Course id
     */
    function getCourseProgressData(courseid){
        PROMISES.GET_COURSE_PROGRESS(courseid).done(function(response) {
            Templates.render('block_remuiblck/course_progress_view', response)
            .done(function(html, js) {
                Templates.replaceNodeContents($(SELECTORS.ROOT).find(SELECTORS.STUDENT_PROGRESS_ELEMENT), html, js);
                createPieProgress(SELECTORS.STUDENT_PROGRESS_ELEMENT);
                courseProgressTable = $(SELECTORS.ROOT).find(SELECTORS.STUDENT_PROGRESS_TABLE).DataTable({
                    "scrollY":        "300px",
                    "scrollCollapse": true,
                    "paging":false,
                    "retrieve": true,
                    "lengthchange":false,
                    "autoWidth": true,
                    "scrollX": true,
                    "search": "Fred",
                    "info":false,
                    language: {
                        searchPlaceholder: "Search"
                    },
                    responsive: true,
                });

                $(SELECTORS.ROOT).find('div.dataTables_filter input').addClass('form-control');
                $(SELECTORS.ROOT).find('div.dataTables_length select').addClass('form-control');

                $('html, body').animate({
                    scrollTop: $(SELECTORS.ROOT).offset().top - 120
                }, 300);
            })
            .fail(function(ex) {
                console.log(ex);
            });
        }).fail(function(ex) {
            $(SELECTORS.ROOT).find('div#analysis-chart-area').hide();
        });

    }


    /**
     * Send message to user
     * @param  {int}    studentid Student id
     * @param  {string} message   Text message
     */
    function sendMessageToUser(studentid, message) {
        PROMISES.SEND_MESSAGE(studentid, message)
        .done(function(response) {
            clearModalFields();
            $(SELECTORS.ROOT).find('.close-message').click();
        })
        .fail(function(ex) {
            Notification.exception(ex);
            $(SELECTORS.ROOT).find('div#analysis-chart-area').hide();
        });
    }

    /**
     * Clear message modal field
     */
    function clearModalFields() {
        $(SELECTORS.ROOT).find(SELECTORS.MESSAGE_HIDDEN).val('');
        $(SELECTORS.ROOT).find(SELECTORS.MESSAGE_AREA).val('');
    }

    function initializeEvents(root) {
        // Destroy the table and send ajax request
        $('body').on('click', root + ' ' + SELECTORS.COURSE_NAME,function(){
            var courseid = $(this).data('courseid');
            // teacherViewTable.destroy();
            $(root).find(SELECTORS.DATA_TABLE).hide();
            getCourseProgressData(courseid);
        })

        // Restore the previous table
        .on('click', root + ' ' + SELECTORS.REVERT,function(){
            courseProgressTable.destroy();
            $(root).find(SELECTORS.STUDENT_PROGRESS_ELEMENT).empty();
            $(root).find(SELECTORS.DATA_TABLE).show();
            $('html, body').animate({
                scrollTop: $(SELECTORS.ROOT).offset().top - 120
            }, 300);
        })

        // This block opens modal and sends message to user
        .on('click', root + ' ' + SELECTORS.CUSTOM_MESSAGE,function(){
            var studentid = $(this).data('studentid');
            $(SELECTORS.MESSAGE_HIDDEN).val(studentid);
        })

        // Send message
        .on('click', root + ' ' + SELECTORS.MESSAGE_SEND,function(){
            var studentid = $(root).find(SELECTORS.MESSAGE_HIDDEN).val();
            var message   = $(root).find(SELECTORS.MESSAGE_AREA).val();
            if(message != '') {
                sendMessageToUser(studentid, message);
            } else {
                $(SELECTORS.MESSAGE_AREA).focus();
            }
        })

        // Toggle description of student progress
        .on('click', SELECTORS.STUDENT_PROGRESS_ELEMENT + ' ' + SELECTORS.TOGGLE_DESCRIPTION, function() {
            $(this).toggleClass('fa-plus');
            $(this).toggleClass('fa-minus');
            $(this).parents(SELECTORS.STUDENT_PROGRESS_ELEMENT).find('.panel-body').toggleClass('show');
        });

        // Teacher courses listing table order pieprogress
        $(root + ' ' + SELECTORS.TABLE).on('order.dt', function () {
           createPieProgress('');
        });

        // Student progress listing table order pieprogress
        $(root + ' ' + SELECTORS.STUDENT_PROGRESS_TABLE).on('order.dt', function () {
           createPieProgress('');
        });
    }

    /**
     * Load task on initialisation
     * @param DOM root block DOM object
     */
    var init = function(root) {
        SELECTORS.ROOT = root;
        $(document).ready(function() {
            setTimeout(function() {
                createPieProgress('');
                createDatatable(root);
                initializeEvents(root);
            }, 2000);
        });
    };
    return {
        init: init
    };
});
