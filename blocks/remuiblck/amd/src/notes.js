define(['jquery', 'core/ajax'], function($, Ajax){
     /* Add Notes Block */
     var SELECTORS = {
        ADD_NOTE_BUTTON: '.add-notes-button',
        ADD_NOTE_SELECT: '.add-notes-select',
        SITE_NOTE: '.site-note',
        COURSE_NOTE: '.course-note',
        PERSONAL_NOTE: '.personal-note',
        STUDENT_LIST: '.select2-studentlist'
     }
    if ($(SELECTORS.ADD_NOTE_SELECT).length) {
        $(SELECTORS.ADD_NOTE_BUTTON).hide();
        $(SELECTORS.STUDENT_LIST).hide();
        var course_id, student_count, user_id, course_name;

        $(SELECTORS.ADD_NOTE_SELECT + ' select').on('change', function () {
            $(SELECTORS.ADD_NOTE_BUTTON).hide();
            course_id = $(this).children(":selected").attr("id");
            course_name = $(this).children(":selected").text();
            if (course_id === undefined) {
                $(SELECTORS.STUDENT_LIST).empty();
                $(SELECTORS.STUDENT_LIST).hide();
                return;
            }
            Ajax.call([{
                methodname: 'block_remuiblck_get_enrolled_users_by_course',
                args: {
                    courseid: course_id
                }
            }])[0].done(function(response) {
                student_count = Object.keys(response).length;
                $(SELECTORS.STUDENT_LIST).show();
                $(SELECTORS.STUDENT_LIST).empty();
                if (student_count) {
                    $(SELECTORS.STUDENT_LIST).append('<option>' + M.util.get_string(
                        "selectastudent", "block_remuiblck") + ' (' + M.util.get_string("total", "moodle") +
                        ': ' + student_count + ')</option>');

                    $.each(response, function (index, student) {
                        $(SELECTORS.STUDENT_LIST).append('<option value="' + student.id + '">' + student.fullname + '</option>');
                    });

                } else {
                    $(SELECTORS.STUDENT_LIST).append('<option>' + M.util.get_string("nousersenrolledincourse",
                        "block_remuiblck", course_name) + '</option>');
                }

            }).fail(function(ex) {
                $(SELECTORS.STUDENT_LIST).html('<option>' + ex.message + '</option>');
            });
        });

        $(SELECTORS.STUDENT_LIST).on('change', function () {
            $(SELECTORS.ADD_NOTE_BUTTON).show();
            user_id = $(this).find('option:selected').val();
            var notes_link = M.cfg.wwwroot + '/notes/edit.php?courseid=' + course_id +
                '&userid=' + user_id + '&publishstate=site';
            $(SELECTORS.ADD_NOTE_BUTTON + ' '+ SELECTORS.SITE_NOTE).attr('href', notes_link);
            notes_link = M.cfg.wwwroot + '/notes/edit.php?courseid=' + course_id +
                '&userid=' + user_id + '&publishstate=public';
            $(SELECTORS.ADD_NOTE_BUTTON + ' '+ SELECTORS.COURSE_NOTE).attr('href', notes_link);
            notes_link = M.cfg.wwwroot + '/notes/edit.php?courseid=' + course_id +
                '&userid=' + user_id + '&publishstate=draft';
            $(SELECTORS.ADD_NOTE_BUTTON + ' '+ SELECTORS.PERSONAL_NOTE).attr('href', notes_link);
        });
    }
    /* End - Add Notes Block */
});
