<?php
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
 * Language file.
 *
 * @package   theme_remui
 * @copyright 2016 Frédéric Massart
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['advancedsettings'] = 'Erweiterte Einstellungen';
$string['backgroundimage'] = 'Hintergrundbild';
$string['backgroundimage_desc'] = 'Das Bild, das als Hintergrund der Site angezeigt werden soll. Das Hintergrundbild, das Sie hier hochladen, überschreibt das Hintergrundbild in den Voreinstellungsdateien Ihres Themas.';
$string['brandcolor'] = 'Markenfarbe';
$string['brandcolor_desc'] = 'Die Akzentfarbe.';
$string['bootswatch'] = 'Bootswatch';
$string['bootswatch_desc'] = 'Eine Bootswatch ist ein Satz von Bootstrap-Variablen und CSS, um Bootstrap zu formatieren';
$string['choosereadme'] = '
<div class="about-remui-wrapper text-center">
    <div class="about-remui m-auto" style="max-width: 1000px;">
        <h1 class="text-center">Welcome To Edwiser RemUI</h1><br>
        <h5 class="text-muted">
        Edwiser RemUI is the new revolution in Moodle User Experience. It has been suitably designed
        to elevate e-learning with custom layouts, simplified navigation, content creation & customization option. <br><br>
        We\'re sure you will enjoy the remodeled look!
        </h5>
        <div class="text-center mt-50">
        <img src="' . $CFG->wwwroot . '/theme/remui/pix/screenshot.jpg" class="w-full" alt="Edwiser RemUI screen shot" style="max-width: 100%;"/>
        </div>
        <br><br>
        <div class="text-center">
            <div class="btn-group text-center" role="group" aria-label="...">
              <div class="btn-group mr-1" role="group">
                <a href="https://knowledgebase.edwiser.org/en/category/edwiser-remui-theme-5sxjyd/" target="_blank" class="btn btn-primary btn-round">FAQ</a>
              </div>
              <div class="btn-group mr-1" role="group">
                <a href="https://edwiser.org/remui/documentation/" target="_blank" class="btn btn-primary btn-round">Documentation</a>
              </div>
              <div class="btn-group" role="group">
                <a href="https://edwiser.org/contact-us/" target="_blank" class="btn btn-primary btn-round">Support</a>
              </div>
            </div>
        </div>
        <br>
        <h1 class="text-center">Personalize Your Theme</h1>
        <h5 class="text-muted text-center">
            We understand that not every LMS is the same. We\'ll work with you to understand your needs, and design and develop a solution to meet your goals.
        </h5>
        <br><br>
        <div class="row wdm_generalbox">
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-cogs"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Theme Customization</h4>
                </div>
            </div>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Functionality Development</h4>
                </div>
            </div>
            <br>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-link"></i>
                </div>
                <div class="iconbox-content">
                    <h4>API Integration</h4>
                </div>
            </div>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-life-ring"></i>
                </div>
                <div class="iconbox-content">
                    <h4>LMS Consultancy</h4>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="text-center">
            <a class="btn btn-primary btn-lg" target="_blank" href="https://edwiser.org/contact-us/">Contact Us</a>&nbsp;&nbsp;
        </div>
    </div>
</div>
<br />';
$string['aboutremui'] = 'Über Edwiser RemUI';
$string['currentinparentheses'] = '(aktuell)';
$string['configtitle'] = 'Edwiser RemUI';
$string['fontsize'] = 'Schriftgröße der Themenbasis';
$string['fontsize_desc'] = 'Geben Sie eine Schriftgröße in% ein';
$string['nobootswatch'] = 'Keiner';
$string['pluginname'] = 'Edwiser RemUI';
$string['presetfiles'] = 'Zusätzliche Voreinstellungsdateien für Themen';
$string['presetfiles_desc'] = 'Voreinstellungsdateien können verwendet werden, um das Erscheinungsbild des Themas drastisch zu ändern.';
$string['preset'] = 'Themenvoreinstellung';
$string['preset_desc'] = 'Wählen Sie eine Voreinstellung, um das Aussehen des Themas weitgehend zu ändern.';
$string['privacy:metadata'] = 'Das remui-Theme speichert keine persönlichen Daten über einen Benutzer.';
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Verwenden Sie dieses Feld, um SCSS- oder CSS-Code bereitzustellen, der am Ende des Stylesheets eingefügt wird.';
$string['rawscsspre'] = 'Rohes anfängliches SCSS';
$string['rawscsspre_desc'] = 'In diesem Feld können Sie den initialisierenden SCSS-Code eingeben, der vor allen anderen Eingaben eingefügt wird. Meistens verwenden Sie diese Einstellung, um Variablen zu definieren.';
$string['region-side-pre'] = 'Rechts';
$string['privacy:metadata:preference:draweropennav'] = 'Die Präferenz des Benutzers zum Ausblenden oder Anzeigen der Schubladenmenünavigation.';
$string['privacy:drawernavclosed'] = 'Die aktuelle Einstellung für die Navigationsleiste ist geschlossen.';
$string['privacy:drawernavopen'] = 'Die aktuelle Einstellung für die Navigationsleiste ist geöffnet.';
/* Course view preference */
$string['privacy:metadata:preference:viewCourseCategory'] = 'Die Art der Anzeige, die der Benutzer für eine Liste von Kursen bevorzugt';
$string['viewCourseCategory_grid'] = 'Um die Kurse im Rasterformat anzuzeigen';
$string['viewCourseCategory_list'] = 'Um die Kurse im Listenformat anzuzeigen';

/* Aside right view preference */
$string['privacy:metadata:preference:aside_right_state'] = 'Ob der Nebenblock rechts offen gehalten oder angedockt werden soll';
$string['aside_right_state_'] = 'Um den Seitenblock rechts als geöffnet anzuzeigen'; // blank value
$string['aside_right_state_overrideaside'] = 'Um den Seitenblock rechts als angedockt anzuzeigen'; // overrideaside

/* Menu view preference */
$string['privacy:metadata:preference:menubar_state'] = 'Die Art der Anzeige, die der Benutzer für die Menüleiste bevorzugt';
$string['menubar_state_fold'] = 'Um die Menüleiste als gefaltet anzuzeigen';
$string['menubar_state_unfold'] = 'Um die Menüleiste als entfaltet anzuzeigen';
$string['menubar_state_open'] = 'Um die Menüleiste als geöffnet anzuzeigen';
$string['menubar_state_hide'] = 'Um die Menüleiste als ausgeblendet anzuzeigen';


// Profile Page.
$string['administrator'] = 'Administrator';
$string['contacts'] = 'Kontakten';
$string['blogentries'] = 'Blog Einträge';
$string['discussions'] = 'Diskussionen';
$string['aboutme'] = 'Über mich';
$string['courses'] = 'Kursen';
$string['interests'] = 'Interesse';
$string['institution'] = 'Abteilung & Institution';
$string['location'] = 'Ort';
$string['description'] = 'Beschreibung';
$string['editprofile'] = 'Profil bearbeiten';
$string['start_date'] = 'Anfangsdatum';
$string['complete'] = 'Komplett';
$string['surname'] = 'Nachname';
$string['actioncouldnotbeperformed'] = 'Vorgang konnte nicht ausgeführt werden!';
$string['enterfirstname'] = 'Geben Sie bitte Ihren Vornamen ein.';
$string['enterlastname'] = 'Geben Sie bitte Ihren Nachnamen ein.';
$string['enteremailid'] = 'Geben Sie bitte Ihre Email Adresse ein.';
$string['enterproperemailid'] = 'Geben Sie bitte Ihre richtige Email Adresse ein.';
$string['detailssavedsuccessfully'] = 'Details erfolgreich gespeichert!';
$string['forgotpassword'] = 'Passwort vergessen?';

// Left Navigation Drawer.
$string['createarchivepage'] = 'Kurs Archiv Seite';
$string['createanewcourse'] = 'Einen neuen kurs erstellen';
$string['remuisettings'] = 'RemUI Einstellungen';

// Right Navigation Drawer.
$string['navbartype'] = 'Farbe der Navigationsleiste';
$string['sidebarcolor'] = 'Farbe der Seitenleiste';
$string['sitecolor'] = 'Farbe der Website';
$string['applysitewide'] = 'Für umfassende Website anwenden';
$string['applysitecolor'] = 'Site-Farbe anwenden';
$string['sidebarpinned'] = 'Seitenleiste fixiert';
$string['sidebarunpinned'] = 'Seitenleiste nicht fixiert';
$string['pinsidebar'] = 'Seitenleiste anheften';
$string['unpinsidebar'] = 'Seitenleiste lösen';

// General Settings.
$string['generalsettings'] = 'Allgemeine Einstellungen';
$string['enableannouncement'] = "Website-Ankündigung aktivieren";
$string['enableannouncementdesc'] = "Eine Website-umfassende Ankündigung für Site Besucher/ Studenten aktivieren.";
$string['announcementtext'] = "Ankündigung";
$string['announcementtextdesc'] = "AAnkündigung Nachricht, der auf die ganze Website gezeigt wird.";
$string['announcementtype'] = "Art der Ankündigung";
$string['announcementtypedesc'] = "info/alert/danger/success";
$string['typeinfo'] = "Ankündigung von Information";
$string['typedanger'] = "Dringende Ankündigung";
$string['typewarning'] = "Ankündigung von Warnung";
$string['typesuccess'] = "Ankündigung von Erfolg";
$string['enablerecentcourses'] = 'Aktivieren Sie Kürzlich besuchte Kurse';
$string['enablerecentcoursesdesc'] = 'Wenn diese Option aktiviert ist, das Dropdown-Menü Letzte Kurse in der Kopfzeile angezeigt.';
$string['enableheaderbuttons'] = 'Kopfzeilenschaltflächen in der Dropdown-Liste anzeigen';
$string['enableheaderbuttonsdesc'] = 'Alle Schaltflächen, die in der Kopfzeile angezeigt werden, werden in eine einzige Dropdown-Liste konvertiert.';
$string['mergemessagingsidebar'] = 'Meldiergremium zusammenfassen';
$string['mergemessagingsidebardesc'] = 'Verschmelzung Message in die rechte Seitenleiste';
$string['courseperpage'] = 'Kurse pro Seite';
$string['courseperpagedesc'] = 'Die Anzahl von Kursen, die pro Seite in der Kurs Archiv Seite angezeigt werden soll.';
$string['none'] = 'Keiner';
$string['fade'] = 'Verblassen';
$string['slide-top'] = 'Schieben Sie nach oben';
$string['slide-bottom'] = 'Schieben Sie nach unten';
$string['slide-right'] = 'Nach rechts schieben';
$string['scale-up'] = 'Vergrößern';
$string['scale-down'] = 'Herunterskalieren';
$string['courseanimation'] = 'Kursanimation';
$string['courseanimationdesc'] = 'Wenn Sie dies aktivieren, wird den Kursen auf der Kursarchivseite eine Animation hinzugefügt';
$string['enablenewcoursecards'] = 'Neue Kurskarten aktivieren';
$string['enablenewcoursecardsdesc'] = 'Wenn Sie dies aktivieren, werden auf der Kursarchivseite neue Kurskarten angezeigt';
$string['activitynextpreviousbutton'] = 'Aktivieren Sie die Schaltfläche Nächste / Vorherige Aktivität';
$string['activitynextpreviousbuttondesc'] = 'Die Schaltfläche Nächste / Vorherige Aktivität wird oben auf der Aktivität angezeigt, um schnell zu wechseln';
$string['disablenextprevious'] = 'Deaktivieren';
$string['enablenextprevious'] = 'Aktivieren';
$string['enablenextpreviouswithname'] = 'Aktiviere mit Aktivitätsname';
$string['logoorsitename'] = 'Das Logo Format auswählen';
$string['logoorsitenamedesc'] = 'Sie können das Aussehen des Seiten Kopfzeile-Logos ändern. Die verfügbare Möglichkeiten sind: Logo- Nur das Logo wird gezeigt; Icon+Sitename - Ein Icon zusammen mit einem Seitennamen wird gezeigt.';
$string['onlylogo'] = 'Nur Logo';
$string['iconsitename'] = 'Icon und Seitenname';
$string['logo'] = 'Logo';
$string['logodesc'] = 'Sie können ein Logo hinzufügen, um es in der Kopfzeile anzuzeigen. Hinweis - Bevorzugte Größe ist 50px. Falls Sie es anpassen wollen, können Sie es aus der Custom CSS Box tun.';
$string['logomini'] = 'LogoMini';
$string['logominidesc'] = 'Sie können die Logomini hinzufügen, die in der Kopfzeile angezeigt werden sollen, wenn die Seitenleiste ausgeblendet ist. Hinweis: Die bevorzugte Höhe beträgt 50 Pixel. Falls Sie anpassen möchten, können Sie dies über das benutzerdefinierte CSS-Feld tun.';
$string['siteicon'] = 'Website-Symbol';
$string['siteicondesc'] = 'Haben Sie kein Logo? Sie können eines aus dieser Liste auswählen <a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_new">hier</a>. Geben Sie nur das ein, was nach dem "fa-" kommt. ';
$string['customcss'] = 'Custom CSS';
$string['customcssdesc'] = 'Sie können das CSS aus der Text Box anpassen. Diese Veränderungen werden auf alle Seiten Ihrer Installation angelegt.';
$string['favicon'] = 'Favicon';
$string['favicondesc'] = 'Ihr Website "Lieblings Icon". Hier können Sie Ihr Favicon für Ihre Website einsetzen.';
$string['fontselect'] = 'Schriftart Auswähler';
$string['fontselectdesc'] = 'Wählen Sie entweder die Standardmäßigen Schriftart oder <a href="https://fonts.google.com/" target="_new">Google web fonts</a>. Bitte zuerst speichern, um die Optionen für Ihre Auswahl zu zeigen.';
$string['fonttypestandard'] = 'Standardmäßige Schriftart';
$string['fonttypegoogle'] = 'Google web Font';
$string['fontname'] = 'Site Font';
$string['fontnamedesc'] = 'Geben sie den genauen Namen von dem Font für Moodle ein.';
$string['googleanalytics'] = 'Google Analytics Tracking ID';
$string['googleanalyticsdesc'] = 'Bitte geben Sie Ihre  Google Analytics Tracking ID ein, um Analyticsauf Ihre Website zu aktivieren . Der  tracking ID Format sollte so sein  [UA-XXXXX-Y].<br />Bitte beachten Sie, dass Sie mit dieser Einstellung Daten an Google Analytics senden und sicherstellen sollten, dass Ihre Nutzer darüber informiert werden. In unserem Produkt werden keine Daten gespeichert, die an Google Analytics gesendet werden.';
$string['enablecoursestats'] = 'Kursstatistik aktivieren';
$string['enablecoursestatsdesc'] = 'Wenn aktiviert, sehen der Administrator, die Manager und der Lehrer die Statistiken zum Kurs auf der Kursseite.';
$string['courseeditbuttonsetting'] = 'Schaltfläche Kurs bearbeiten';
$string['courseeditbuttonsetting_desc'] = 'Mit dieser Einstellung können Sie dem Kurskopf eine zusätzliche Schaltfläche zum Bearbeiten von Kursen hinzufügen.';
$string['enabledictionary'] = 'Wörterbuch aktivieren';
$string['enabledictionarydesc'] = 'Wenn aktiviert, wird die Dictionary-Funktion aktiviert und zeigt die Bedeutung des ausgewählten Textes in der Tooltip an.';
$string['more'] = 'Mehr...';

// Frontpage Old String
// Home Page Settings.
$string['homepagesettings'] = 'Startseiten Einstellungen';
$string['frontpagedesign'] = 'Frontpage Design';
$string['frontpagedesigndesc'] = 'Dieser Abschnitt bezieht sich auf den Designstil der Startseite.';
$string['frontpagechooser'] = 'Wählen Sie das Frontpage-Design';
$string['frontpagechooserdesc'] = 'Wählen Sie Ihr Frontpage-Design.';
$string['frontpagedesignold'] = 'Standard altes Design';
$string['frontpagedesignolddesc'] = 'Standard-Dashboard wie zuvor.';
$string['frontpagedesignnew'] = 'Neues Design';
$string['frontpagedesignnewdesc'] = 'Frisches neues Design mit mehreren Abschnitten. Sie können einzelne Abschnitte auf der Startseite konfigurieren.';
$string['newhomepagedescription'] = 'Klicken Sie in der Navigationsleiste auf "Homepage", um zum "Homepage Builder" zu gelangen und eine eigene Homepage zu erstellen';
$string['frontpageloader'] = 'Lade das Loader-Bild für die Startseite hoch';
$string['frontpageloaderdesc'] = 'Dadurch wird der Standardlader durch Ihr Image ersetzt';
$string['frontpageimagecontent'] = 'Inhalt von Kopfzeile';
$string['frontpageimagecontentdesc'] = ' Dieses Teil bezieht sich auf den obene Abschnitt Ihrer Startseite';
$string['frontpageimagecontentstyle'] = 'Stil';
$string['frontpageimagecontentstyledesc'] = 'Sie können aus statischen Inhalten und Slider Inhalten auswählen.';
$string['staticcontent'] = 'Statische Inhalte';
$string['slidercontent'] = 'Slider Inhalte';
$string['addtext'] = 'Text hinzufügen';
$string['defaultaddtext'] = 'Bildung ist einen lange erprobt Weg zum Erfolg.';
$string['addtextdesc'] = 'Hier können Sie den Text hinzufügen, der auf der Startseite gezeigt werden soll, möglichst in HTML.';
$string['uploadimage'] = 'Bild hochladen';
$string['uploadimagedesc'] = 'Hier können Sie Ihr eigenes Bild hochladen ';
$string['video'] = 'iframe Embedded code';
$string['videodesc'] = 'Hier können Sie den iframe Embedded Code des Videos einsetzen, der integriert werden soll.';
$string['contenttype'] = 'Art des Inhalts auswählen';
$string['contentdesc'] = 'Sie können aus dem Bild oder Video url auswählen.';
$string['imageorvideo'] = 'Bild / Video';
$string['image'] = 'Bild';
$string['videourl'] = 'Video URL';
$string['slideinterval'] = 'Schiebeintervall';
$string['slideintervalplaceholder'] = 'Positive Ganzzahl in Millisekunden.';
$string['slideintervaldesc'] = 'Sie können die Übergangszeit zwischen den Folien einstellen. Falls es nur eine Folie gibt, wird diese Option gar keinen Einfluss darauf haben.';
$string['slidercount'] = 'Anzahl von Folien';
$string['slidercountdesc'] = '';
$string['one'] = '1';
$string['two'] = '2';
$string['three'] = '3';
$string['four'] = '4';
$string['five'] = '5';
$string['eight'] = '8';
$string['twelve'] = '12';
$string['slideimage'] = 'Bilder für Slider hochladen';
$string['slideimagedesc'] = 'Sie können hier ein Bild als Inhalt für den Slider hochladen.';
$string['sliderurl'] = 'Schieberegler Text Button Link';
$string['slidertext'] = 'Slider Text hinzufügen';
$string['defaultslidertext'] = '';
$string['slidertextdesc'] = 'Sie können den Text Inhalt von Ihrem Slider einsetzen, möglichst im HTML.';
$string['sliderbuttontext'] = 'Text Button von Slider hinzufügen ';
$string['sliderbuttontextdesc'] = 'Sie können eine Text Button auf Ihr Slider einsetzen.';
$string['sliderurldesc'] = 'Sie können den Link der Seite einsetzen, wo der Benutzer nur einmal umgeleitet werden, nachdem er/sie auf die Text Button klickt.';
$string['sliderautoplay'] = 'Slider Autoplay Einstellen';
$string['sliderautoplaydesc'] = 'Ja auswählen, wenn Sie einen automatischen Übergang in Ihrer Slideshow haben möchten.';
$string['true'] = 'Ja';
$string['false'] = 'Nein';
$string['frontpageblocks'] = 'Body Inhalt';
$string['frontpageblocksdesc'] = 'Sie können eine Überschrift für Ihren Webseiten-Inhalt einsetzen';
$string['frontpageblockdisplay'] = 'Über uns Abschnitt';
$string['frontpageblockdisplaydesc'] = 'Sie können den Bereich "Über uns" ein- oder ausblenden, Sie können ihn auch im gitterformat anzeigen';
$string['donotshowaboutus'] = 'Nicht zeigen';
$string['showaboutusinrow'] = 'Abschnitt in einer Reihe anzeigen';
$string['showaboutusingridblock'] = 'Abschnitt im Grid-Block anzeigen';

// About Us.
$string['frontpageaboutus'] = 'Titelseite Über uns';
$string['frontpageaboutusdesc'] = 'Dieser Teil ist für die Starteseite Über uns';
$string['frontpageaboutustitledesc'] = 'Titel hinzufügen zu Über uns Sektion';
$string['frontpageaboutusbody'] = 'Body Beschreibung für Über uns Abschnitt';
$string['frontpageaboutusbodydesc'] = 'Eine kurze Beschreibung dieses Abschnitts';
$string['enablesectionbutton'] = 'Buttons in diesem Abschnitt aktivieren ';
$string['enablesectionbuttondesc'] = 'Buttons auf dem Body Abschnitt aktivieren.';
$string['sectionbuttontextdesc'] = 'Geben Sie den Text für den Button in diesem Teil .';
$string['sectionbuttonlinkdesc'] = 'Geben Sie die URL für diesen Teil.';
$string['frontpageblocksectiondesc'] = 'Titel zu diesem Teil hinzufügen .';
/* block section 1 */
$string['frontpageblocksection1'] = 'Body Titel für den erste Teil';
$string['frontpageblockdescriptionsection1'] = 'Body Beschreibung für den erste Teil';
$string['frontpageblockiconsection1'] = 'Font-Awesome iocn Teil 1';
$string['sectionbuttontext1'] = 'Button Text für Teil1';
$string['sectionbuttonlink1'] = 'URL Link Teil1';
/* block section 2 */
$string['frontpageblocksection2'] = 'Body Titel für den zweite Teil';
$string['frontpageblockdescriptionsection2'] = 'Body Beschreibung für den zweite Teil';
$string['frontpageblockiconsection2'] = 'Font-Awesome iocn Teil  2';
$string['sectionbuttontext2'] = 'Button Text für Teil2';
$string['sectionbuttonlink2'] = 'URL Link Teil2';
/* block section 3 */
$string['frontpageblocksection3'] = 'Body Titel für dritte Teil';
$string['frontpageblockdescriptionsection3'] = 'Body Beschreibung für das dritte Teil';
$string['frontpageblockiconsection3'] = 'Font-Awesome iocn Teil  3';
$string['sectionbuttontext3'] = 'Button Text für Teil3';
$string['sectionbuttonlink3'] = 'URL Link Teil3';
/* block section 4 */
$string['frontpageblocksection4'] = 'Body Titel für den vierte Teil';
$string['frontpageblockdescriptionsection4'] = 'Body Beschreibung für den vierte Teil';
$string['frontpageblockiconsection4'] = 'Font-Awesome iocn Teil  4';
$string['sectionbuttontext4'] = 'Button Text für Teil4';
$string['sectionbuttonlink4'] = 'URL Link für Teil4';
$string['defaultdescriptionsection'] = 'Holistisch nutzen die Technologie rechtzeitig durch Unternehmenfällen';
$string['frontpageaboutus'] = 'Titelseite Über uns';
$string['frontpageaboutusdesc'] = 'Dieser Teil ist für die Starteseite Über uns';
$string['enablefrontpageaboutus'] = 'Über uns Teil aktivieren';
$string['enablefrontpageaboutusdesc'] = 'Den Über uns Teil auf die Startseite aktivieren.';
$string['frontpageaboutusheading'] = 'Über uns Überschrift';
$string['frontpageaboutusheadingdesc'] = 'Überschrift für default heading Text für diesen Teil ';
$string['frontpageaboutustext'] = 'Über uns Text';
$string['frontpageaboutustextdesc'] = 'Geben Sie den über uns Text für die Startseite ein.';
$string['frontpageaboutusdefault'] = '<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
              Ut enim ad minim veniam.</p>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                  eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.Lorem ipsum dolor sit amet,
                  consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                  labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur
                  adipisicing elit, sed do eiusmod tempor incididunt
                  ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>';
$string['testimonialcount'] = 'Testimonial Anzahl';
$string['testimonialcountdesc'] = 'Anzahl von Testimonials, die angezeigt werden müssen.';
$string['testimonialimage'] = 'Testimonial Bild';
$string['testimonialimagedesc'] = 'Bild des Benutzers, um mit dem Testimonial anzuzeigen';
$string['testimonialname'] = 'Benutzername';
$string['testimonialnamedesc'] = 'Benutzername';
$string['testimonialdesignation'] = 'Designation des Benutzers';
$string['testimonialdesignationdesc'] = 'Designation des Benutzers.';
$string['testimonialtext'] = 'Testimonial des Benutzers';
$string['testimonialtextdesc'] = 'Was der Benutzer sagt';
$string['frontpageblockimage'] = 'Bild hochladen';
$string['frontpageblockimagedesc'] = 'Sie können dazu ein Bild als Inhalt hochladen.';
$string['frontpageblockiconsectiondesc'] = 'Sie können irgendein Icon auswählen <a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_new">list</a>. Geben Sie den Text erst nach "fa-". ';
$string['frontpageblockdescriptionsectiondesc'] = 'Eine kurze Beschreibung über den Titel';


// Footer Page Settings.
$string['footersettings'] = 'Fußzeile Einstellungen';
$string['socialmedia'] = 'Social Media Einstellungen';
$string['socialmediadesc'] = 'Geben Sie den social media Link für Ihre Seite ein.';
$string['facebooksetting'] = 'Facebook Einstellungen';
$string['facebooksettingdesc'] = 'Geben Sie Ihren Facebook Link ein. Zum Beispiel. https://www.facebook.com/EndlessBrain';
$string['twittersetting'] = 'Twitter Einstellungen';
$string['twittersettingdesc'] = 'Geben Sie Ihren Twitter Link ein. Zum Beispiel. https://www.twitter.com/EndlessBrain';
$string['linkedinsetting'] = 'Linkedin Einstellungen';
$string['linkedinsettingdesc'] = 'Geben Sie Ihren Linkedin Link ein. Zum Beispiel. https://www.linkedin.com/in/pagename <br/> Falls Ihr Land Indien ist.';
$string['gplussetting'] = 'Google Plus Einstellungen';
$string['gplussettingdesc'] = 'Geben Sie Ihren Google Plus Page Link ein. Zum Beispiel. https://plus.google.com/pagename';
$string['youtubesetting'] = 'YouTube Einstellungen';
$string['youtubesettingdesc'] = 'Geben Sie Ihren YouTube Link ein.Zum Beispiel. https://www.youtube.com/channel/UCU1u6QtAAPJrV0v0_c2EISA';
$string['instagramsetting'] = 'Instagram Einstellungen';
$string['instagramsettingdesc'] = 'Geben Sie Ihren Instagram Link ein. Zum Beispiel. https://www.instagram.com/name';
$string['pinterestsetting'] = 'Pinterest Einstellungen';
$string['pinterestsettingdesc'] = 'Geben Sie Ihren Pinterest Link ein. Zum Beispiel. https://www.pinterest.com/name';
$string['quorasetting'] = 'Quora Einstellungen';
$string['quorasettingdesc'] = 'Geben Sie Ihren Quora Link ein. Zum Beispiel. https://www.quora.com/name';
$string['footerbottomtext'] = 'Fußzeilentext links unten';
$string['footerbottomlink'] = 'Fußzeile unten links link';
$string['footerbottomlinkdesc'] = 'Geben Sie den Link für den unteren linken Teil der Fußzeile. Zum Beispiel. http://www.EndlessBrain.com';
$string['footercolumn1heading'] = 'Fußzeile Inhalt für Spalte 1 (Links)';
$string['footercolumn1headingdesc'] = 'Dieses Teil bezieht sich auf den unteren Teil ( Spalte 1) von Ihrer Startseite.';
$string['footercolumn1title'] = 'Fußzeile Spalte 1 Titel ';
$string['footercolumn1titledesc'] = 'Hier können Sie einen Titel für die erste Spalte der Fußzeile hinzufügen.';
$string['footercolumn1customhtml'] = 'Custom HTML';
$string['footercolumn1customhtmldesc'] = 'Sie können HTML für Fußzeile Spalte 1 von der oberen Text Box anpassen.';
$string['footercolumn2heading'] = 'Fußzeile Inhalt für Spalte  2 (Mittel)';
$string['footercolumn2headingdesc'] = 'Dieser Teil bezieht sich auf den untere Teil ( Spalte 2) von Ihrer Startseite.';
$string['footercolumn2title'] = 'Fußzeile Spalte 2 Titel';
$string['footercolumn2titledesc'] = 'Hier können Sie einen Titel für die erste Spalte der Fußzeile hinzufügen.';
$string['footercolumn2customhtml'] = 'Custom HTML';
$string['footercolumn2customhtmldesc'] = 'Sie können HTML für Fußzeile Spalte 2 von der oberen Text Box anpassen.';
$string['footercolumn3heading'] = 'Fußzeile Inhalt für Spalte  3 (Rechts)';
$string['footercolumn3headingdesc'] = 'Dieser Teil bezieht sich auf den unteren Teil ( Spalte 3) von Ihrer Startseite.';
$string['footercolumn3title'] = 'Fußzeile Spalte 3 Titel';
$string['footercolumn3titledesc'] = 'Hier können Sie einen Titel für die erste Spalte der Fußzeile hinzufügen.';
$string['footercolumn3customhtml'] = 'Custom HTML';
$string['footercolumn3customhtmldesc'] = 'Sie können HTML für Fußzeile Spalte 3 von der oberen Text Box anpassen.';
$string['footerbottomheading'] = 'Einstellung Fußzeile Unterer Teil ';
$string['footerbottomdesc'] = 'Hier können Sie Ihren eigene Link einfügen, den Sie in dem unteren Teil der Fußzeile eingeben wollen.';
$string['footerbottomtextdesc'] = 'Geben Sie den Text für das Unten-Rechts Teil der Fußzeile ein.';
$string['poweredbyedwiser'] = 'Powered by Edwiser';
$string['poweredbyedwiserdesc'] = 'Uncheck to remove  \'Powered by Edwiser\' from your site.';

// Login Page Settings.
$string['loginsettings'] = 'Einstellungen Log-in Seite ';
$string['navlogin_popup'] = ' Login Popup aktivieren';
$string['navlogin_popupdesc'] = ' Login popup in Kopfzeile aktivieren.';
$string['loginsettingpic'] = 'Hier ein Bild hochladen';
$string['loginsettingpicdesc'] = 'Dieses Bild wird in dem Hintergrund des Log-in Formulars angezeigt.';
$string['signuptextcolor'] = 'Registrierungs Panel Textfarbe';
$string['signuptextcolordesc'] = 'Wählen Sie die Textfarbe für das Registrierungs Panel aus, die zu Ihrem Hintergrund Bild Ihrer Log-in Seite passt.';
$string['left'] = "Links";
$string['right'] = "Rechts";
$string['remember_me'] = "Mich erinnern";
$string['brandlogopos'] = "Markenlogo Position";
$string['brandlogoposdesc'] = "Ob aktiviert, wird das Markenlogo in der rechten Seitenleiste über dem Anmeldeformular angezeigt.";
$string['brandlogotext'] = "Seitenbeschreibung";
$string['brandlogotextdesc'] = "Fügen Sie Text für die Website-Beschreibung hinzu, die auf der anmelden- und Registrierungsseite angezeigt wird. Lassen Sie dieses Feld leer, wenn Sie keine Beschreibung eingeben möchten.";
$string['loginpagelayout'] = 'Anmeldeseitenlayout';
$string['loginpagelayoutdesc'] = '';
$string['centerlayout'] = 'Zentriertes Layout';
$string['overlaylayout'] = 'Rechtes Überlagerungslayout';

// License Settings.
$string['licensenotactive'] = '<strong>Alert!</strong>Lizenz ist nicht aktiviert, bitte <strong>aktivieren</strong> Sie die Lizenz in den RemUI Einstellungen.';
$string['licensenotactiveadmin'] = '<strong>Alert!</strong> Lizenz ist nicht aktiviert, bitte <strong>aktivieren</strong> Sie die Lizenz<a href="'.$CFG->wwwroot.'/admin/settings.php?section=themesettingremui#remuilicensestatus" > hier</a>.';
$string['activatelicense'] = 'Lizenz aktivieren';
$string['deactivatelicense'] = 'Lizenz deaktivieren';
$string['renewlicense'] = 'Lizenz erneuern';
$string['active'] = 'Aktiv';
$string['notactive'] = 'Nicht Aktiv';
$string['expired'] = 'Abgelaufen';
$string['licensekey'] = 'Lizenzschlüssel';
$string['licensestatus'] = 'Lizen Status';
$string['noresponsereceived'] = 'Keinen Antwort von dem Server. Bitte später noch einmal versuchen.';
$string['licensekeydeactivated'] = 'Lizenzschlüssel ist deaktiviert.';
$string['siteinactive'] = 'Seite ist deaktiviert (Auf Lizenz aktivieren klicken, um das Plugin zu aktivieren).';
$string['entervalidlicensekey'] = 'Bitte den gültigen Lizenzschlüssel eingeben.';
$string['licensekeyisdisabled'] = 'Ihr Lizenzschlüssel ist deaktiviert.';
$string['licensekeyhasexpired'] = "Ihr Lizenzschlüssel ist abgelaufen. Bitte erneuern Sie ihn.";
$string['licensekeyactivated'] = "Ihr Lizenzschlüssel ist aktiviert.";
$string['enterlicensekey'] = "Bitte den Lizenzschlüssel eingeben.";
$string['edwiserremuilicenseactivation'] = 'Edwiser RemUI Lizenz Aktivierung';
$string['nolicenselimitleft'] = 'Maximale Aktivierungsgrenze erreicht, Keine Aktivierungen links.';

// News And Updates Page.
$string['newsandupdates'] = 'Nachrichte und Updates';
$string['newupdatemessage'] = 'Neue Update für RemUI steht zur Verfügung.';
$string['currentversionmessage'] = 'Ihre aktuelle Version ist';
$string['downloadupdate'] = ' Update herunterladen';
$string['latestversionmessage'] = 'Sie benutzen die neueste Version von RemUI.';
$string['rateremui'] = 'RemUI bewerten';
$string['fullname']  = 'Voller Name';
$string['providefeedback'] = 'Bitte geben Sie Ihren Feedback von RemUI ein';
$string['sendfeedback'] = 'Feedback schicken';
$string['recentnews'] = 'Neueste Nachricht';

// About Edwiser RemUI.
$string['aboutsettings'] = 'Über Edwiser RemUI';
$string['notenrolledanycourse'] = 'Sie haben sich für keinen angemeldet.';

/* My Course Page */
$string['resume'] = 'Fortsetzen';
$string['start'] = 'Beginnen';
$string['completed'] = 'Fertig';

// Course.
$string['graderreport'] = 'Note Report';
$string['enroluser'] = 'Benutzer anmelden';
$string['activityeport'] = 'Aktivitätsreport';
$string['editcourse'] = 'Kurs bearbeiten';
$string['sections'] = "Abschnitte";

// Next Previous Activity.
$string['activityprev'] = 'Vorherige Aktivität';
$string['activitynext'] = 'Nächste Aktivität';

// Login Page.
$string['signin'] = 'Sich anmelden';
$string['signup'] = 'Sich registrieren';
$string['noaccount'] = 'Kein Konto?';

// Incourse Page.
$string['backtocourse'] = 'Kursüberblick';

// Header Section.
$string['togglefullscreen'] = 'Vollbild umschalten';
$string['recent'] = 'Kürzlich';

// Course Stats.
$string['enrolledusers'] = 'Registrierte <br>Studenten';
$string['studentcompleted'] = 'Studenten <br>Abgeschlossen';
$string['inprogress'] = 'In <br>Bearbeitung';
$string['yettostart'] = 'Noch <br>zu beginnen';

// Footer Content.
$string['followus'] = 'Uns folgen';
$string['poweredby'] = 'Von Edwiser RemUI betrieben';

// Course Archive Page.
$string['mycourses'] = "Meine Kurse";
$string['allcategories'] = 'Alle kategorien';
$string['categorysort'] = 'Kategorien sortieren';
$string['sortdefault'] = 'Sortieren (keine)';
$string['sortascending'] = 'Sortieren von A bis Z.';
$string['sortdescending'] = 'Sortieren Sie Z nach A';

// Dashboard Blocks.
$string['viewcourse'] = "KURS ANSEHEN";
$string['searchcourses'] = "Kurse suchen";

$string['hiddencourse'] = 'Versteckter Kurs';

// Usage tracking
$string['enableusagetracking'] = "Aktivieren von Usage Trakcing";
$string['enableusagetrackingdesc'] = "<strong>HINWEIS ZUR NUTZUNGSVERFOLGUNG</strong>

<hr class='text-muted' />

<p>Edwiser sammelt ab sofort anonyme Daten, um Produkt Nutzungsstatistiken zu generieren.</p>

<p>Diese Informationen werden uns helfen, die Entwicklung in die richtige Richtung zu lenken und die Edwiser-Gemeinschaft gedeiht.</p>

<p>Allerdings erfassen wir während dieses Vorgangs nicht Ihre personenbezogenen Daten oder Ihre Schüler. Sie können dies über das Plugin deaktivieren, wenn Sie diesen Dienst deaktivieren möchten.</p>

<p>Eine Übersicht der erhobenen Daten finden Sie <strong><a href='https://forums.edwiser.org/topic/67/anonymously-tracking-the-usage-of-edwiser-products' target='_blank'>hier</a></strong>.</p>";