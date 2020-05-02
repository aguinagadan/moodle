// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Section manager class
 *
 * @module     local_remuihomepage/sectionmanager
 * @copyright  (c) 2018 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([
    'jquery',
    'core/ajax',
    'core/templates',
    'core/notification',
    'theme_remui/notice',
    'theme_remui/events'
], function(
    $,
    Ajax,
    Templates,
    Notification,
    Notice,
    RemUIEvents
) {
    let SECTIONSELECTOR = "";

    // Keep the loaded sections in memory.
    var loadedSections = [];

    // Keep section deletion timers.
    var sectionDeletion = [];

    function SectionManager () {
        this.sectioncontainer = '#page-site-index .home-sections';
        this.templateprefix = 'local_remuihomepage/';
        this.templateprefix += $('body').hasClass('remui_lite') ? 'lite_' : '';
    }

    /**
     * Set loaded section
     * @param {Number} id   section is
     * @param {String} json sections sections list
     */
    SectionManager.prototype.setLoadedSection = function(id, json) {
        loadedSections[id] = json;
    };

    /**
     * Get loaded section
     * @param  {Number} instanceid Instance id of section
     * @return {String}            Section json
     */
    SectionManager.prototype.getLoadedSection = function(instanceid) {
        return loadedSections[instanceid];
    };

    /**
     * Set name of section
     * @param {String} name Section name
     */
    SectionManager.prototype.setSectionName = function(name) {
        this.name = name;
    };

    /**
     * Set instance id
     * @param {Number} id Instance id
     */
    SectionManager.prototype.setInstanceid = function(id) {
        this.instanceid = id;
        SECTIONSELECTOR = 'section[data-instance="' + id + '"]';
    };

    /**
     * Template prefix string
     * @param  {String} str Prefix string
     * @return {String}     Return template name with prefix
     */
    SectionManager.prototype.templateprefixer = function(str) {
        return this.templateprefix + str;
    };

    /**
     * Activate loader to load section on page
     * @param  {object} data section data
     */
    SectionManager.prototype.activateLoader = function(data) {
        var data = this.getJsonParsedData(data);
        let callback = (function() {
            this.setInstanceid(data.id);
            this.saveSectionsOrder();
            $('body').trigger('scroll');
            this.scrollToSection('section[data-instance="' + data.id + '"]');
        }).bind(this);

        this.renderTemplate(this.templateprefixer(data.sectionname), data, this.sectioncontainer, callback);
    };

    /**
     * Scroll To the section
     * @param {String} selector Section selector
     */
    SectionManager.prototype.scrollToSection = function(selector) {
        var offset = $(selector).offset().top - 65;
        $('html, body').animate({scrollTop: offset}, 1000);
    };

    /**
     * Render template and append in sections list
     * @param  {string}   template   Template name
     * @param  {object}   configdata section data
     * @param  {string}   selector   Container selector
     * @param  {function} callback   Callback to execute after rendering template
     */
    SectionManager.prototype.renderTemplate = function(template, configdata, selector, callback) {
        Templates.render(template, configdata)
        .then(function(html, js) {
            Templates.appendNodeContents(selector, html, js);
            $('body').trigger({
                type: RemUIEvents.SECTION_ADDED,
                configdata: configdata
            });
            if (callback != null) {
                callback();
            }
        });
    };

    /**
     * Get section element by instance id
     * @param  {Number} instanceid Section instance id
     * @return {DOM}               Section element
     */
    SectionManager.prototype.getSectionElement = function(instanceid) {
        if (instanceid !== false) {
            this.setInstanceid(instanceid);
        }
        return $('body').find(SECTIONSELECTOR);
    };

    /**
     * Play video of visible slide
     * @param  {Number} instanceid Section instance id
     */
    SectionManager.prototype.playSliderVideo = function(instanceid) {
        var slider = this.getSectionElement(instanceid);
        slider.find('.carousel-item:not(.active) video').each(function(index, video) {
            video.pause();
        });
        slider.find('.carousel-item.active video').each(function(index, video) {
            video.play();
        });
    };

    /**
     * Render first template from the configdata array
     * @param  {array}  configdata Configuration array
     * @param  {string} selector   Root container selector
     */
    SectionManager.prototype.renderTemplates = function(configdata, selector) {
        let json = configdata.pop();
        let section = this.getJsonParsedData(json);
        this.setLoadedSection(section.id, json);
        section.lazyloading = true;
        Templates.render('local_remuihomepage/common', section)
        .done((function(html, js) {
            html = $(html).height(window.innerHeight + 'px');
            Templates.appendNodeContents(selector, html, js);
            // Show section immediatly if appear animation is disabled.
            if (appearanimation == false) {
                $('body').trigger('scroll');
            }
            this.hideAllSectionsLoader();
            jQuery(window).scrollTop(0);
            if (configdata.length == 0) {
                return;
            }
            this.renderTemplates(configdata, selector);
        }).bind(this)).fail((function(ex) {
            this.hideAllSectionsLoader();
            Notification.exception(ex);
        }).bind(this));
    };

    /**
     * Returns parsed data For this instance using $.parseJSON which is deprecated method,
     * find alternative for this and JSON.parse method add unnecessary thing in object.
     * @param  {String} data Json data
     * @return {Object}      Parsed json data
     */
    SectionManager.prototype.getJsonParsedData = function(data) {
        return JSON.parse(data);
    };

    /**
     * Add section to page
     */
    SectionManager.prototype.addSection = function() {
        Ajax.call([{
            methodname: 'local_remuihomepage_create_section_instance',
            args: {sectionname: this.name},
            done: this.activateLoader.bind(this),
            fail: Notification.exception
        }]);
    };

    /**
     * Hide main loader which covers all sections area till sections are loaded
     */
    SectionManager.prototype.hideAllSectionsLoader = function() {
        $('.sections-loader-wrapper').removeClass('show');
        setTimeout(function() {
            $('.sections-loader-wrapper').hide();
            $(window).trigger('scroll');
        }, 300);
    };

    /**
     * Hide main loader which covers all sections area till sections are loaded
     */
    SectionManager.prototype.showAllSectionsLoader = function() {
        $('.sections-loader-wrapper').addClass('show').css('display', 'block');
    };

    /**
     * Show section loader
     * @param  {int}  instanceid Instance id of section
     * @param  {Boolean} show       true if show loader else false
     */
    SectionManager.prototype.showSectionLoader = function(instanceid, show) {
        $('.section-loader-wrapper[data-instance="' + instanceid + '"]').toggleClass('d-none', !show);
    };

    /**
     * Render all section on page load
     * @param {response} response Ajax call response
     */
    SectionManager.prototype.addAllSections = function(response) {
        response.sections.reverse();
        if (response.sections.length == 0) {
            this.hideAllSectionsLoader();
            return;
        }
        this.renderTemplates(response.sections, this.sectioncontainer);
    };

    /**
     * Load all section configuration
     */
    SectionManager.prototype.LoadAllSections = function() {
        Ajax.call([
        {
            methodname: 'local_remuihomepage_fetch_all_instances',
            args: {},
            done: this.addAllSections.bind(this),
            fail: Notification.exception
        }
        ]);
    };

    /**
     * Reload section if configuration is updated.
     * @param  {Object}   response js object with updation status and section context
     * @param  {Function} callback Callback function to run after reloading section
     */
    SectionManager.prototype.reloadSection = function(response, callback) {
        if (response.success == false) {
            return;
        }
        let context = JSON.parse(response.context);
        this.setInstanceid(context.id);
        let reloadingSection = $(SECTIONSELECTOR);
        Templates.render(this.templateprefixer(context.sectionname), context)
        .done((function(html, js) {
            html = $(html);
            // Show section immediatly if appear animation is disabled.
            if (appearanimation == false) {
                $(html).removeClass('invisible');
            }
            Templates.replaceNode(reloadingSection, html, js);
            $('body').trigger({
                type: RemUIEvents.SECTION_UPDATED,
                configdata: context
            });
            if (context.sectionname == 'slider') {
                this.playSliderVideo(context.id);
            }
            $('body').trigger('scroll');
            if (callback != null) {
                callback();
            }
        }).bind(this))
        .fail((function(ex) {
            this.showSectionLoader(this.instanceid, false);
            Notification.exception(ex);
        }).bind(this));
    };

    /**
     * Update sections settings
     * @param  {int}    instanceid Section instance id
     * @param  {string} formdata   Serialized section form data
     */
    SectionManager.prototype.updateSection = function(instanceid, formdata) {
        this.showSectionLoader(instanceid, true);
        Ajax.call([
        {
            methodname: 'local_remuihomepage_update_section_instance',
            args: {instanceid: instanceid, jsonformdata: formdata},
            done: (function(response) {
                Notice.success(M.util.get_string('sectionupdated', 'local_remuihomepage'), 4000);
                this.reloadSection(response, null);
            }).bind(this),
        fail: (function(ex) {
                this.showSectionLoader(instanceid, false);
                Notification.exception(ex);
        }).bind(this)
        }
        ]);
    };

    /**
     * Enable deletion timer or cancel it
     * @param  {Number}  id     Section id
     * @param  {Boolean} action true to start timer, false to clear timer
     */
    SectionManager.prototype.deletionTimer = function(id, action) {
        // If user cancel deletion then clear timer.
        if (action == false) {
            clearInterval(sectionDeletion[id]);
        }
        var section = this.getSectionElement(false);
        var timer = $(section).find('.cancel-delete-section .section-deletion-timer');

        // Set section deletion timer.
        sectionDeletion[id] = setInterval((function() {
            var time = parseInt($(timer).text()) - 1;
            $(timer).text(time);

            // If time is 0 then clear timer and remove section.
            if (time == 0) {
                clearInterval(sectionDeletion[id]);
                $(section).remove();
                this.saveSectionsOrder();
                $('body').trigger('scroll');
            }
        }).bind(this), 1000);
    };

    /**
     * Mark section instance as deleted or undo deletion
     * @param  {Number}  id      section id
     * @param  {Boolean|Number}  action Deletion action false to cancel, true to mark as delete. 2 if confirm deletion
     */
    SectionManager.prototype.deleteInstance = function(id, action) {
        this.showSectionLoader(id, true);
        this.setInstanceid(id);

        // Clear timer and delete section from dom when admin confirm form deletion.
        if (action == 2) {
            this.deletionTimer(id, false);
            this.getSectionElement(false).remove();
            this.saveSectionsOrder();
            $('body').trigger('scroll');
            return;
        }

        if (action == true) {
            Notice.success(M.util.get_string('sectiondeleted', 'local_remuihomepage'), 4000);
        }

        Ajax.call([
        {
            methodname: 'local_remuihomepage_delete_section_instance',
            args: {instanceid: id, action: action},
            done: (function(response) {
                this.reloadSection(response, (function() {

                    // Start deletion timer.
                    this.deletionTimer(id, action);
                }).bind(this));
                this.saveSectionsOrder();
            }).bind(this),
        fail: (function(ex) {
                this.showSectionLoader(id, false);
                Notification.exception(ex);
        }).bind(this)
        }
        ]);
    };

    /**
     * Get sections order
     */
    SectionManager.prototype.getSectionsOrder = function() {
        let sections = $('body').find('section[data-instance]');
        let order = [];
        sections.each(function(index, section) {
            if ($(section).find('.section-delete-overlay')) {
                order.push($(section).data('instance'));
            }
        });
        return order;
    };

    /**
     * Save order of section in user preference
     */
    SectionManager.prototype.saveSectionsOrder = function() {
        if (transparentheader) {
            $('body').toggleClass('has-slider animate-header', $('body').find('section[data-instance]:first-child').is('.section-slider'));
        }
        let order = this.getSectionsOrder();
        return Ajax.call([{
            methodname: 'local_remuihomepage_save_sections_order',
            args: {
                order
            }
        }])[0];
    };

    /**
     * Reorder the section eigther up or down
     * @param  {int} instanceid Section instanceid
     * @param  {int} move       Direction(1 for down, -1 for up)
     */
    SectionManager.prototype.reorderSection = function(instanceid, move, saveorder) {
        this.setInstanceid(instanceid);
        let section = $(SECTIONSELECTOR);
        if (move == -1 && !section.is(':first-child')) {// Move upside.
            section.prev().insertAfter(section);
        } else if(move == 1 && !section.is(':last-child')) {// Move downside.
            section.next().insertBefore(section);
        } else {
            return;
        }
        if (saveorder == false) {
            return;
        }
        this.saveSectionsOrder().fail((function(ex) {
            Notification.exception(ex);
            this.reorderSection(instanceid, move * -1, false);
        }).bind(this));
    };

    /**
     * Update visibility of section
     * @param  {Number}  instanceid Section instance id
     * @param  {Boolean} visible    Section visibility
     */
    SectionManager.prototype.updateVisibility = function(instanceid, visible) {
        var visibilityButton = this.getSectionElement(instanceid).find('button.section-visibility');
        Ajax.call([
        {
            methodname: 'local_remuihomepage_update_section_visibility',
            args: {
                id: instanceid,
                visible: visible
            },
            done: (function(response) {
                $(visibilityButton).data('visible', visible);
                $(visibilityButton).find('.icon').toggleClass('fa-eye', visible);
                $(visibilityButton).find('.icon').toggleClass('fa-eye-slash', !visible);
                if(visible){
                    $(visibilityButton).attr("title", 'Hide');
                    $(visibilityButton).find('.icon').attr("title", 'Hide');
                } else {
                    $(visibilityButton).attr("title", 'Show');
                    $(visibilityButton).find('.icon').attr("title", 'Show');
                }
            }).bind(this),
        fail: Notification.exception
        }
        ]);
    };

    var SECTIONMANAGER = new SectionManager();

    return SECTIONMANAGER;
});
