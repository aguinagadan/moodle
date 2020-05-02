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

define([
    'jquery',
    'core/ajax'
], function(
    $,
    Ajax,
) {
    const DEFAULTHEX = '1177d1';
    let colorhex;
    const COLORTOHEX = [
        {color: 'primary', hex: '62a8ea'},
        {color: 'brown', hex: '8d6658'},
        {color: 'cyan', hex: '57c7d4'},
        {color: 'green', hex: '46be8a'},
        {color: 'grey', hex: '757575'},
        {color: 'indigo', hex: '677ae4'},
        {color: 'orange', hex: 'f2a654'},
        {color: 'pink', hex: 'f96197'},
        {color: 'purple', hex: '926dde'},
        {color: 'red', hex: 'f96868'},
        {color: 'teal', hex: '3aa99e'}
    ];
    $('#skintoolsSiteColor input[type="radio"][name="skintoolsNavbar"]').on('change', function () {
        let color = this.value;
        if (color == 'customcolor') {
            $('.site-colorpicker').show();
        } else {
            $('.site-colorpicker').hide();
            // Update sitecolor.
            updateSetting('sitecolor', color);
            // Update sitecolor hex.
            colorhex = COLORTOHEX.filter(el => el.color == color);
            if (colorhex.length) {
                colorhex = colorhex[0].hex;
            } else {
                colorhex = DEFAULTHEX;
            }
            updateSetting('sitecolorhex', colorhex);
            $('.navbar-brand').attr('style', `background-color: #${colorhex} !important`);
            $('.nav-inverse').attr('style', `background-color: #${colorhex} !important`);
            $('#page-footer').attr('style', `background-color: #${colorhex} !important`);
        }
    });

    function updateSetting(configname, configvalue) {
        let service_name = 'theme_remui_set_setting';
        Ajax.call([
            {
                methodname: service_name,
                args: { configname : configname, configvalue: configvalue }
            }
        ]);
    }

    $(document).on('change', '.site-colorpicker', function() {
        let color = this.value.split('#')[1];
        updateSetting('sitecolor', 'customcolor');
        updateSetting('sitecolorhex', color);
    });

    $('#skintoolsNavbar-inverse').on('change', function () {
        let inverse = this.value;
        let color;
        if (!this.checked) {
            inverse = "";
            color = "ffffff";
        } else {
            color = colorhex;
        }
        updateSetting('navbarinverse', inverse);
        $('.navbar').toggleClass('nav-inverse');
        if (colorhex != undefined) {
            $('.navbar').attr('style', `background-color: #${color} !important`);
        }
    });

    $('#skintoolsSidebar input[type="radio"][name="skintoolsSidebar"]').on('change', function () {
        let color = this.value;
        if (color == 'site-menubar-light') {
            $('#nav-drawer').removeClass('dark');
            updateSetting('sidebarcolor', 'site-menubar-light');
        } else {
            $('#nav-drawer').addClass('dark');
            updateSetting('sidebarcolor', '');
        }
    });
});
