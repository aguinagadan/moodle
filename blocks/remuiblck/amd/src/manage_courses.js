define([
    'jquery',
    'core/ajax',
    'core/notification',
    'core/modal_factory',
    'core/modal_save_cancel',
    'core/modal_events',
    'block_remuiblck/manage_courses_view',
    'block_remuiblck/manage_courses_filters',
    'core/chartjs',
    'block_remuiblck/jquery.dataTables',
    'block_remuiblck/dataTables.bootstrap4'
], function(
    $,
    ajax,
    Notification,
    ModalFactory,
    ModalSaveCancel,
    ModalEvents,
    ManageCoursesView,
    ManageCoursesFilters
) {

    var SELECTORS = {
        VIEW_REPORT: '.remui-view-course-report',
        STATS_TAB: '#wdm-userstats',
        STATS_TABLE: '#userstats-table',
        EXPORT_WRAPPER: '#userstats-table_wrapper .wdm-export-buttons',
        EXPORT: '.wdm-manage-course-export-csv',
        STATS_CHART: '#coursestats-chart',
        STATS_FILTER_INPUT: '#userstats-table_filter input',
        DROPPING_STUDENT_MESSAGE: '.dropping-student-message'
    };

    var PROMISES = {
        /**
         * Get course report promise
         * @param  {int}    courseid Course id
         * @return promise           Ajax promise object
         */
        GET_COURSE_REPORT: function(courseid) {
            return ajax.call([{
                methodname: 'block_remuiblck_get_course_report',
                args: {
                    courseid: courseid
                }
            }])[0];
        },

        /**
         * Get dropping off students list promise
         * @param {int}     courseid Course id
         * @param {String}  search   Search query
         * @param {int}     length   Number of rows per page
         * @param {int}     start    Starting row number
         * @param {object}  order    Sorting order
         * @return promise           Ajax promise object
         */
        GET_DROPPING_OFF_STUDENTS: function(courseid, search, length, start, order) {
            return ajax.call([{
                methodname: 'block_remuiblck_get_dropping_off_students',
                args: {
                    courseid: courseid,
                    search: search,
                    length: length,
                    start: start,
                    order: order
                }
            }])[0];
        },

        /**
         * Export dropping off students list promise
         * @param {int}     courseid Course id
         * @param {string}  search   search query
         * @return promise           Ajax promise object
         */
        EXPORT_DROPPING_OFF_STUDENTS: function(courseid, search) {
            return ajax.call([{
                methodname: 'block_remuiblck_export_dropping_off_students',
                args: {
                    courseid: courseid,
                    search  : search
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
            return ajax.call([{
                methodname: 'block_remuiblck_send_message',
                args: {
                    studentid: studentid,
                    messagetext: messagetext
                }
            }])[0];
        }
    };
    $('body').on('click', SELECTORS.VIEW_REPORT, function() {
        event.preventDefault();
        var _this = this;
        var trigger = $('#create-modal');
        ModalFactory.create({
            title: $(_this).attr('title')
        }, trigger).done(function(modal) {
            modal.modal.addClass('modal-lg');

            // Destroy when hidden.
            modal.getRoot().on(ModalEvents.hidden, function() {
                modal.destroy();
            }).addClass('fade');

            // Show loading icon till load modal body
            modal.setBody('<i class="fa fa-circle-o-notch fa-spin fa-fw" aria-hidden="true"></i>');

            // Fetch body using ajax request
            PROMISES.GET_COURSE_REPORT($(_this).data('course-id'))
            .done(function(response) {
                modal.setBody(response);
                if (modal.getRoot().find(SELECTORS.STATS_CHART).length != 0) {
                    var ctx = modal.getRoot().find(SELECTORS.STATS_CHART)[0].getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: [
                                M.util.get_string('studentcompleted', 'block_remuiblck'),
                                M.util.get_string('inprogress', 'block_remuiblck'),
                                M.util.get_string('yettostart', 'block_remuiblck')
                            ],
                            datasets: [{
                                backgroundColor: [
                                    studentcompletedcolor,
                                    inprogresscolor,
                                    yettostartcolor
                                ],
                                data: [
                                    modal.getRoot().find(SELECTORS.STATS_CHART).data('studentcompleted'),
                                    modal.getRoot().find(SELECTORS.STATS_CHART).data('inprogress'),
                                    modal.getRoot().find(SELECTORS.STATS_CHART).data('yettostart')
                                ]
                            }]
                        },
                        options: {
                            legend: {
                                display: false
                            }
                        }
                    });
                }
            })
            .fail(Notification.exception);

            // Show modal
            setTimeout(modal.show(), 100);
        });
        return;
    }).on('click', SELECTORS.STATS_TAB, function() {
        if ($(SELECTORS.STATS_TABLE).is('.dataTable')) {
            return;
        }
        $(SELECTORS.STATS_TABLE).DataTable({
            "bPaginate": true,
            "bServerSide": true,
            "language": {
                "searchPlaceholder": M.util.get_string('searchnameemail', 'block_remuiblck'),
                "emptyTable"       : M.util.get_string('nostudentsenrolled', 'block_remuiblck')
            },
            "dom": '<"wdm-export-buttons">frtip',
            "initComplete": function(settings, json) {
                $(SELECTORS.EXPORT_WRAPPER).append(
                    "<button class='wdm-manage-course-export-csv btn btn-primary' data-course-id='" + $(this).data('course-id') + "'>" + M.util.get_string(
                        'exportcsv',
                        'block_remuiblck'
                    ) + "</button>"
                );
            },
            "ajax": function(data, callback, settings) {
                PROMISES.GET_DROPPING_OFF_STUDENTS(
                    $(this).data('course-id'),
                    data.search.value,
                    data.length,
                    data.start,
                    data.order[0]
                ).done(function(response) {
                    callback(response);
                }).fail(Notification.exception);
            },
            "columns": [
                { "className": "pb-0 pt-0", "data": "name" },
                { "className": "pb-0 pt-0", "data": "email" },
                { "className": "pb-0 pt-0", "data": "enroltimestart" },
                { "className": "pb-0 pt-0", "data": "lastaccess" }
            ],
            "rowCallback": function( row, data, index ) {
                if(index%2 == 0){
                    $(row).addClass('bg-grey-100');
                }else{
                    $(row).addClass('bg-grey-200');
                }
            }
        });
    }).on('click', SELECTORS.EXPORT, function() {
        var _this = this;
        PROMISES.EXPORT_DROPPING_OFF_STUDENTS(
            $(_this).data('course-id'),
            $(SELECTORS.STATS_FILTER_INPUT).val()
        ).done(function(response) {
            var file = $('<a></a>');
            $('body').append(file);
            $(file).attr('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(response.filedata));
            $(file).attr('download', response.filename).hide()[0].click();
            $(file).remove();
        }).fail(Notification.exception);
    }).on('click', SELECTORS.DROPPING_STUDENT_MESSAGE, function() {
        var _this = this;
        var trigger = $('#create-modal');
        ModalFactory.create({
            type: ModalFactory.types.SAVE_CANCEL,
            title: M.util.get_string('sendmessage', 'core_message')
        }, trigger).done(function(modal) {

            // Set button text as send
            modal.setSaveButtonText(M.util.get_string('send', 'core_message'));

            // Add textarea field in body

            modal.setBody('<label>'+M.util.get_string('sendmessageto', 'core_message', $($(_this).parent('td').siblings()[0]).html())+'</label><textarea class="form-control message" rows="5" autocomplete="off"></textarea>');

            // Destroy when hidden.
            modal.getRoot().on(ModalEvents.hidden, function() {
                modal.destroy();
            });

            // Send message when save button is clicked
            modal.getRoot().on(ModalEvents.save, function(event) {
                var studentid = $(_this).data('student-id');
                var message = $(this).find('.message').val();
                if (message != '') {
                    PROMISES.SEND_MESSAGE(studentid, message)
                    .done(function(response) {
                        modal.destroy();
                    })
                    .fail(function(ex) {
                        Notification.exception(ex);
                    });
                }
            }).addClass('modal-success fade');
            modal.modal.addClass('modal-center');

            // Show modal
            setTimeout(modal.show(), 100);
        });
    });

    var init = function(root) {
        $(document).ready(function() {
            ManageCoursesView.init(root);
            ManageCoursesFilters.init(root);
        });
    };
    return {
        init: init
    };
});
