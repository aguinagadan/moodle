define('theme_remui/settings', ['jquery'], function($){
    

    /**
     * Toggle visibility of element
     * @param  {Array}   elements   Elements/Settings list
     * @param  {Boolean} visibility True to show Element and false to hide
     */
    function toggle_elements(elements, visibility) {
        if (elements == undefined) {
            return;
        }
        elements.forEach(function(element) {
            $('#admin-' + element).toggle(visibility == 1);
            $('#id_s_theme_remui_' + element).trigger('change');
        });
    }

    /**
     * Get value of triggering element
     * @param  {String} name Name of element/setting
     * @return {String}      Current/Changes value of element
     */
    function get_value(name) {
        var element = $('#id_s_theme_remui_' + name);
        if (element.is('input[type="checkbox"]')) {
            return element.is(':checked');
        }
        return element.val();
    }

    /**
     * Check current value of element and apply visibility of dependent element
     * @param  {String}  name       Name of triggered element
     * @param  {Object}  options    Object containing
     * @param  {Boolean} hide       If true then elements will be hidden forcebly. User if triggering element is hidden.
     */
    function check_settings(name, options, hide) {
        if (!Array.isArray(options)) {
            options = [options];
        }
        var value = get_value(name);
        options.forEach(function(condition) {
            if (value == condition.value) {
                if (Object.prototype.hasOwnProperty.call(condition, 'show')) {
                    toggle_elements(condition.show, true ^ hide);
                }
                if (Object.prototype.hasOwnProperty.call(condition, 'hide')) {
                    toggle_elements(condition.hide, false);
                }
            }
        });
    }

    /**
     * Attach change listener to element
     */
    function attach_listener() {
        Object.keys(remuisettings).forEach(function(name) {
            $('#id_s_theme_remui_' + name).on('change', function() {
                if ($('#admin-' + name).css('display') == 'none') {
                    check_settings(name, remuisettings[name], true);
                    return;
                }
                check_settings(name, remuisettings[name], false);
            })
        });
        Object.keys(remuisettings).forEach(function(name) {
            check_settings(name, remuisettings[name], false);
            $('#id_s_theme_remui_' + name).trigger('change');
        });
    }
    return {
        init: function() {
            attach_listener('');   
        }
    };
});