/* jshint ignore:start */
define(['jquery', 'core/ajax', 'core/notification', 'theme_remui/jquery-asPieProgress', 'theme_remui/aspieprogress'], function($, Ajax) {
    $('#editprofile .form-horizontal #btn-save-changes').click(function() {
        var SELECTORS = {
            ERROR: 'div#error-message',
            DANGER: 'alert-danger',
            SUCCESS: 'alert-success'
        };
        $(SELECTORS.ERROR).show();
        $(SELECTORS.ERROR).removeClass(SELECTORS.DANGER).addClass(SELECTORS.SUCCESS);
        $(SELECTORS.ERROR).find('p').html("Saving...");
        var fname = $('#first_name').val();
        var lname = $('#surname').val();
        //var emailid = $('#standard_email').val();
        var description = $.trim($('#description').val());
        var city = $.trim($('#city').val());
        var country = $('#editprofile .form-horizontal #country option:selected').val();

        if (fname === '') {
            $(SELECTORS.ERROR).show();
            $(SELECTORS.ERROR).removeClass(SELECTORS.SUCCESS).addClass(SELECTORS.DANGER);
            $(SELECTORS.ERROR).find('p').html(M.util.get_string('enterfirstname', 'theme_remui'));
            $('#first_name').focus();
            return false;
        }
        if (lname === '') {
            $(SELECTORS.ERROR).show();
            $(SELECTORS.ERROR).removeClass(SELECTORS.SUCCESS).addClass(SELECTORS.DANGER);
            $(SELECTORS.ERROR).find('p').html(M.util.get_string('enterlastname', 'theme_remui'));
            $('#surname').focus();
            return false;
        }

        /*if (country === M.util.get_string('selectcountry', 'theme_remui')) {
            countryname = '';
            country = '';
        }*/
        var promise = Ajax.call([{
            methodname: 'theme_remui_save_user_profile_settings',
            args: {
                fname,
                lname,
                description,
                city,
                country
            }
        }])[0];
        promise.done(function(response) {
            $(SELECTORS.ERROR).show();
            $(SELECTORS.ERROR).removeClass(SELECTORS.DANGER).addClass(SELECTORS.SUCCESS);
            $(SELECTORS.ERROR).find('p').css('margin', '0').html(M.util.get_string('detailssavedsuccessfully', 'theme_remui'));
            $('.profile-user').text(fname + " " + lname);
            $('.usermenu a span.usertext').text(fname + " " + lname);
            $('#user-description').text(description);
        })
        .fail(function(ex) {
            $(SELECTORS.ERROR).removeClass(SELECTORS.SUCCESS).addClass(SELECTORS.DANGER);
            $(SELECTORS.ERROR).find('p').css('margin', '0').html(error + ' : ' + errorThrown + ', '+ M.util.get_string('actioncouldnotbeperformed', 'theme_remui'));
            Notification.exception(ex);
        });
    });

    // initiliaze coruse progress in profile
    $('.remui-course-progress').asPieProgress({
        namespace: 'asPieProgress'
    });
});
/* jshint ignore:end */
