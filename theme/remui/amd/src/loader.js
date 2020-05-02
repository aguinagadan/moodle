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
 * Template renderer for Moodle. Load and render Moodle templates with Mustache.
 *
 * @module     core/templates
 * @package    core
 * @class      templates
 * @copyright  2015 Damyon Wiese <damyon@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      2.9
 */
define([
    'jquery',
    'theme_remui/tether',
    'core/event',
    'theme_remui/aria',
    'theme_remui/breakpoints',
    'theme_remui/drawer',
    'theme_remui/notice',
    'core/str',
    'core/pubsub',
    'theme_remui/pending',
    'theme_remui/util',
    'theme_remui/alert',
    'theme_remui/button',
    'theme_remui/carousel',
    'theme_remui/collapse',
    'theme_remui/dropdown',
    'theme_remui/modal',
    'theme_remui/scrollspy',
    'theme_remui/tab',
    'theme_remui/tooltip',
    'theme_remui/popover',
    'theme_remui/skintool'
    ], function(
        $,
        Tether,
        Event,
        Aria,
        breakpoints,
        Drawer,
        Notice,
        str,
        PubSub
    ) {

    window.jQuery = $;
    window.Tether = Tether;
    Drawer.init();

    // We do twice because: https://github.com/twbs/bootstrap/issues/10547
    $('body').popover({
        trigger: 'focus',
        selector: "[data-toggle=popover][data-trigger!=hover]"
    });

    $("html").popover({
        container: "body",
        selector: "[data-toggle=popover][data-trigger=hover]",
        trigger: "hover",
        delay: {
            hide: 500
        }
    });

    // We need to call popover automatically if nodes are added to the page later.
    Event.getLegacyEvents().done(function(events) {
        $(document).on(events.FILTER_CONTENT_UPDATED, function() {
            $('body').popover({
                selector: '[data-toggle="popover"]',
                trigger: 'focus'
            });

        });
    });

    // Settings update on change
    $(`#id_s_theme_remui_frontpagechooser`).change(function() {
        window.onbeforeunload = null;
        this.form.submit();
    });
    Aria.init();
    // Open Right Sidebar.
    $(".page-aside-switch .fa-angle-left").on('click', function() {
        $("body").toggleClass('sidebar-open');
        if (!$("body").hasClass("hasblocks")) {
            $(".page-aside-switch").removeClass('d-flex');
            $(".page-aside-switch").hide();
        }
    });

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if ($("body").hasClass("editing") && $("body").hasClass("hasblocks")) {
            $(".page-aside-switch .fa-angle-left").trigger('click');
        }
        resetSidebar();
        
        // collapsible menu in header implementation
        // moves excess menu items to 3 dot menu on resize and based on available screen space

        // greedy menu js implementation
        var $btn = $('nav.greedy .menu-toggle');
        var $vlinks = $('nav.greedy .links');
        var $hlinks = $('nav.greedy .hidden-links');
        var numOfItems = 0;
        var totalSpace = 0;
        var breakWidths = [];
        var availableSpace, numOfVisibleItems, requiredSpace;
        
        // Get initial state
        $vlinks.children().outerWidth(function(i, w) {
            totalSpace += w;
            numOfItems += 1;
            breakWidths.push(totalSpace);
        });

        function wdmCollapsibleNavMenu() {        
            // Get instant state
            availableSpace = $vlinks.width() - 10;
            numOfVisibleItems = $vlinks.children().length;
            requiredSpace = breakWidths[numOfVisibleItems - 1];
        
            // There is not enought space
            if (requiredSpace > availableSpace) {
            $vlinks.children().last().prependTo($hlinks);
            numOfVisibleItems -= 1;
            wdmCollapsibleNavMenu();
            // There is more than enough space
            } else if (availableSpace > breakWidths[numOfVisibleItems]) {
            $hlinks.children().first().appendTo($vlinks);
            numOfVisibleItems += 1;
            }
            // Update the button accordingly
            $btn.attr("count", numOfItems - numOfVisibleItems);
            if (numOfVisibleItems === numOfItems) {
            $btn.addClass('hidden');
            } else $btn.removeClass('hidden');
        }

        // move collpasible menu toggle in front of ul list on mobiel devices
        // function wdmMoveMenuToggleButton(resize = 0) {
        //     if($(window).width() < 481) {
        //         $( "button.menu-toggle" ).insertBefore( $( ".wdm-custom-menus.links" ) );
        //     }

        //     if(resize == 1) {
        //         if($(window).width() > 480) {
        //             $( "button.menu-toggle" ).insertAfter( $( ".wdm-custom-menus.links" ) );
        //         }
        //     }
        // }

        // init collapsible nav menu
        wdmCollapsibleNavMenu();
        //wdmMoveMenuToggleButton();

        // hide / show hidden-links ul on click button
        $btn.on('click', function() {
            var currLeft = $(this).offset().left - 25;
            $hlinks.css({
                left: currLeft + "px"
            })
            $hlinks.toggleClass('hidden');
        });

        // close when clicking somewhere else
        $(document.body).on('click', function (evt) {
            let IGNORED_ELS = 'ul.hidden-links, button.menu-toggle, .modal, .alertify, .-handled-lick';
            if (evt.button === 0 && !$('ul.hidden-links').hasClass('hidden')) {
                var target = evt.target;
                if (target === evt.currentTarget || !$(target).closest(IGNORED_ELS).length) {
                    $('ul.hidden-links').addClass('hidden');
                }
            }
        });

        // resize menu when drawer opens closes
        PubSub.subscribe('nav-drawer-toggle-end', function(open) {
            wdmCollapsibleNavMenu();
        });

        // Window listeners
        $(window).resize(function() {
            wdmCollapsibleNavMenu();
            //wdmMoveMenuToggleButton(1);
        });

        /** collapsible menu JS ends */

        // Auto hide messaging bar when not merged in sidebar
        if ($('[data-region="right-hand-drawer"]').parents('#sidebar-message').length == 0) {
            $(document.body).on('click', function (evt) {
                if (evt.button ===0 && !jQuery('[data-region="right-hand-drawer"]').hasClass('hidden')) {
                    // Hide Message Drawer if click outsite
                    var IGNORE_DRAWER_BTN = '[data-region="right-hand-drawer"], [href="#sidebar-message"]';
                    var target = evt.target;
                    if (target === evt.currentTarget || !jQuery(target).closest(IGNORE_DRAWER_BTN).length) {
                        jQuery('[data-region="right-hand-drawer"]').addClass("hidden");
                        jQuery(IGNORE_DRAWER_BTN).removeClass('active');
                    }
                }
            });
        } else {
            // Prevent messaging bar toggle when merged in sidebar
            $('.page-aside .nav-item .nav-link[href="#sidebar-message"]').click(function(e) {
                $('[data-region="right-hand-drawer"]').removeClass('hidden');
            });
        }
    });

    // Pin & Unpin Right Sidebar.
    $(".page-aside-switch .fa-thumb-tack").on('click', function() {
        $("body").removeClass('sidebar-open');
        $("body").toggleClass('sidebar-pinned');
        if($('body').hasClass('sidebar-pinned')) {
            M.util.set_user_preference('pin_aside', 'true');
            Notice.info(M.util.get_string('sidebarpinned', 'theme_remui'));
            $(this).prop('title', M.util.get_string('unpinsidebar', 'theme_remui'));
        } else {
            M.util.set_user_preference('pin_aside', '');
            Notice.info(M.util.get_string('sidebarunpinned', 'theme_remui'));
            $(this).prop('title', M.util.get_string('pinsidebar', 'theme_remui'));
        }
    });

    // Close Right Sidebar on click outside.
    $(document.body).on('click', function (evt) {
        let IGNORED_ELS = '.page-aside, .modal, .alertify, .-handled-lick';
        if (evt.button === 0 && $('body').hasClass('sidebar-open')) {
            var target = evt.target;
            if (target === evt.currentTarget || !$(target).closest(IGNORED_ELS).length) {
                $('body').removeClass('sidebar-open');
                $(".page-aside-switch").addClass('d-flex');
                $(".page-aside-switch").show();
            }
        }
    });

    // Scroll to top.
    $("#gotop").click(function() {
        $('html, body').animate({scrollTop:0}, $(window).scrollTop() / 6);
        return false;
    });

    // Hide and Show Go to top button.
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
          $('#gotop').removeClass("d-none").addClass("d-flex");
        } else {
          $('#gotop').removeClass("d-flex").addClass("d-none");
        }
    });

    // Display Submenu on Hover on closed sidebar.
    $('#nav-drawer .list-group-item:not(.activity):not([data-indent="1"])').hover(function () {
        if (!$('#nav-drawer').hasClass('closed')) {
            return;
        }
        let distanceFromTop = $(this).position().top + 66;
        let screenHeight = $(window).height();
        $(this).addClass('hovered');
        $('.media-body').css('top', `${distanceFromTop}px`);
        // Sub Menu.
        let submenuid = $(this).attr('data-target');
        if (submenuid != undefined && submenuid != "" ) {
            let submenu = $(`${submenuid}`);
            if (submenu.length > 0) {
                let topdistance;
                if (distanceFromTop < (screenHeight / 2)) {
                    topdistance = distanceFromTop + 52;
                } else {
                    topdistance = distanceFromTop - $(submenu).height();
                }
                $(submenu).css('top', `${topdistance}px`);
                $(submenu).addClass('pop-over');
            }
        }
        // My Courses data-indent = 1.
        let subcourseskey = $(this).attr('data-key');
        if (subcourseskey != undefined && subcourseskey == "mycourses") {
            var subcourses = $('#nav-drawer .mycoursesubmenu');
            if (distanceFromTop < (screenHeight / 2)) {
                topdistance = distanceFromTop + 52;
                $(subcourses).css('top', `${topdistance}px`);
                $(subcourses).addClass('pop-over');
            } else {
                $(subcourses).addClass('pop-over');
                topdistance = distanceFromTop - $(subcourses).height();
                $(subcourses).css('top', `${topdistance}px`);
            }
        }
    }, function () {
        $(this).removeClass('hovered');
        $('.sub-menu').removeClass('pop-over');
        $('#nav-drawer .mycoursesubmenu').removeClass('pop-over');
    });

    $('.sub-menu').hover(function () {
        let elid = $(this).attr('id');
        let parentel = $(`#nav-drawer .list-group-item[data-target="#${elid}"]`);
        $(parentel).trigger('mouseenter');
        $(parentel).trigger('hover');
        $(parentel).trigger('mouseover');
    }, function () {
        let elid = $(this).attr('id');
        let parentel = $(`#nav-drawer .list-group-item[data-target="#${elid}"]`);
        $(parentel).trigger('mouseout');
    });

    // My Courses.
    $('#nav-drawer .mycoursesubmenu').hover(function () {
        let elkey = $(this).attr('data-parent-key');
        let parentel = $(`#nav-drawer .list-group-item[data-key="${elkey}"]`);
        $(parentel).trigger('mouseenter');
        $(parentel).trigger('hover');
        $(parentel).trigger('mouseover');
    }, function () {
        let elkey = $(this).attr('data-parent-key');
        let parentel = $(`#nav-drawer .list-group-item[data-key="${elkey}"]`);
        $(parentel).trigger('mouseout');
    });

    // flat navigation mycourses ul dropdown support
    $('#nav-drawer .toggle-menu').click(function(e) {
        e.preventDefault();
        let key = $(this).attr('data-key');
        $('#nav-drawer .toggle-menu').toggleClass('show');
        $(`#nav-drawer a.list-group-item[data-parent-key="${key}"]`).toggleClass('show');
    });

    // toggle section show or hide in default course formats
    $('.sectionname .toggle-section').click(function() {
        let parentEl = $(this).parent().parent().parent();
        let sectionEl = parentEl.find('ul.section');
        if (sectionEl.length) {
            $(this).toggleClass('down');
            $(sectionEl).toggleClass('hidden');
        }
    });

    // Add signup form fields placeholders.
    $(".signupform .fcontainer .form-group").each(function(index) {
        var label = $.trim($(".col-form-label", this).text());
        $(".felement input", this).attr('placeholder', label); 
    });

    // Function for fullscreen.
    $('#toggleFullscreen').click(function () {
        $(this).toggleClass('collapse');
        toggleFullScreen();
    });

    function toggleFullScreen() {
        if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        } else {
            var element = $('html')[0];
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
        }
    }

    // Move Quiz Timer from sidebar to main content in mobile view
    if (document.getElementById('quiz-timer')) {
        var quiztimer = document.querySelector('#quiz-timer');
        var breadcrumb = document.querySelector("#region-main");

        if (quiztimer) {
            breadcrumb.parentNode.insertBefore(quiztimer, breadcrumb);
        }
    }

    // Fix to get message details when no blocks are present.
    $('.page-aside .page-aside-switch').click(function() {
        $(this).next().find('.nav-item .nav-link.active').trigger('click');
    });

    // close drawer and sidebar automatically on smaller window size
    function resetSidebar() {
        var width = $(window).width();
        if (width < 992) {
            if ($('body').hasClass('drawer-open-left')) {
                $('button[data-action="toggle-drawer"]').trigger('click');
            }
            if ($('body').hasClass('sidebar-open')) {
                $('.page-aside-switch .fa-angle-left').trigger('click');
            }
            $('body').removeClass('sidebar-pinned');
        }
    }

    // resize listner for reset sidebar function
    $(window).resize(function () {
        resetSidebar();
    });

    $('.navbar-toggler').click(function() {
        $('.navbar-nav.right-menu').toggleClass('show');
    });
});
