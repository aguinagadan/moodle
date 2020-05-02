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

$string['advancedsettings'] = 'Réglages avancés';
$string['backgroundimage'] = 'Image de fond';
$string['backgroundimage_desc'] = 'L\'image à afficher en tant qu\'arrière-plan du site. L\'image d\'arrière-plan que vous téléchargez ici remplacera l\'image d\'arrière-plan dans vos fichiers de préréglage de thème.';
$string['brandcolor'] = 'Couleur de la marque';
$string['brandcolor_desc'] = 'La couleur d\'accent.';
$string['bootswatch'] = 'Bootswatch';
$string['bootswatch_desc'] = 'Une montre de démarrage est un ensemble de variables Bootstrap et css pour styler Bootstrap';
$string['choosereadme'] = '
<div class="about-remui-wrapper text-center">
    <div class="about-remui m-auto" style="max-width: 1000px;">
        <h1 class="text-center">Bienvenu dans le thème Edwiser RemUI</h1><br>
        <h4 class="text-muted">Le thème Edwiser RemUI est la nouvelle révolution dans l\'expérience utilisateur de Moodle.
        Il a été spécialement conçu pour enrichir le e-learning avec une présentation personnalisée, une navigation simplifiée
        et des options personnalisables de création de contenu.<br><br>
        Nous sommes certain que vous apprécierez la possibilité de remodelé le design de votre site Moodle.!
        </h4>
        <div class="text-center mt-50">
        <img src="' . $CFG->wwwroot . '/theme/remui/pix/screenshot.jpg" class="w-full" alt="Edwiser RemUI screen shot" style="max-width: 100%;"/>
        </div>
        <br><br>
        <div class="text-center">
            <div class="btn-group text-center" role="group" aria-label="...">
              <div class="btn-group mr-1" role="group">
                <a href="https://knowledgebase.edwiser.org/en/category/edwiser-remui-theme-5sxjyd/" target="_blank" class="btn btn-primary btn-round">FAQ</a>&nbsp;
              </div>
              <div class="btn-group mr-1" role="group">
                <a href="https://edwiser.org/remui/documentation/" target="_blank" class="btn btn-primary btn-round">Documentation</a>&nbsp;
              </div>
              <div class="btn-group" role="group">
                <a href="https://edwiser.org/contact-us/" target="_blank" class="btn btn-primary btn-round">Support</a>
              </div>
            </div>
        </div>
        <br>
        <h1 class="text-center">Personnalisez votre thème</h1>
        <h4 class="text-muted text-center">
            Nous comprenons que tous les LMS ne sont pas les mêmes. Nous allons travailler avec vous pour comprendre vos besoins, concevoir et développer une solution pour atteindre vos objectifs.
        </h4>
        <br><br>
        <div class="row wdm_generalbox">
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-cogs"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Personnalisation du thème</h4>
                </div>
            </div>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Développement de fonctionnalités</h4>
                </div>
            </div>
            <br>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-link"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Intégration d\'API</h4>
                </div>
            </div>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-life-ring"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Conseil LMS</h4>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="text-center">
            <a class="btn btn-primary btn-lg" target="_blank" href="https://edwiser.org/contact-us/">Contactez-nous</a>&nbsp;&nbsp;
        </div>
    </div>
</div>
<br />';
$string['aboutremui'] = 'About Edwiser RemUI';
$string['currentinparentheses'] = '(actuel)';
$string['configtitle'] = 'Edwiser RemUI';
$string['fontsize'] = 'Taille de la base de thème';
$string['fontsize_desc'] = 'Entrez une taille de police en%';
$string['nobootswatch'] = 'Aucun';
$string['pluginname'] = 'Edwiser RemUI';
$string['presetfiles'] = 'Fichiers de préréglage de thème supplémentaires';
$string['presetfiles_desc'] = 'Les fichiers prédéfinis peuvent être utilisés pour modifier radicalement l\'apparence du thème.';
$string['preset'] = 'Thème prédéfini';
$string['preset_desc'] = 'Choisissez un préréglage pour changer le look du thème.';
$string['privacy:metadata'] = 'Le thème remui ne stocke aucune donnée personnelle concernant un utilisateur.';
$string['rawscss'] = 'SCSS brut';
$string['rawscss_desc'] = 'Utilisez ce champ pour fournir le code SCSS ou CSS qui sera injecté à la fin de la feuille de style.';
$string['rawscsspre'] = 'SCSS initial brut';
$string['rawscsspre_desc'] = 'Dans ce champ, vous pouvez fournir le code d\'initialisation SCSS, il sera injecté avant tout le reste. La plupart du temps, vous utiliserez ce paramètre pour définir des variables.';
$string['region-side-pre'] = 'Droite';
$string['privacy:metadata:preference:draweropennav'] = 'Préférence de l\'utilisateur pour masquer ou afficher la navigation dans le menu du tiroir.';
$string['privacy:drawernavclosed'] = 'La préférence actuelle pour le tiroir de navigation est fermée.';
$string['privacy:drawernavopen'] = 'La préférence actuelle pour le tiroir de navigation est ouverte';
/* Course view preference */
$string['privacy:metadata:preference:course_view_state'] = 'Le type d\'affichage que l\'utilisateur préfère pour la liste des cours';
$string['course_view_state_description_grid'] = 'Pour afficher les cours en format de grille';
$string['course_view_state_description_list'] = 'Pour afficher les cours en format de liste';

/* Course view preference */
$string['privacy:metadata:preference:viewCourseCategory'] = 'Le type d\'affichage que l\'utilisateur préfère pour la liste des cours';
$string['viewCourseCategory_grid'] = 'Pour afficher les cours en format de grille';
$string['viewCourseCategory_list'] = 'Pour afficher les cours en format de liste';

/* Aside right view preference */
$string['privacy:metadata:preference:aside_right_state'] = 'Si le bloc de côté à droite doit rester ouvert ou amarré';
$string['aside_right_state_'] = 'Pour afficher le bloc de côté à droite comme ouvert'; // blank value
$string['aside_right_state_overrideaside'] = 'Pour afficher le bloc de côté à droite comme ancré'; // overrideaside

/* Menu view preference */
$string['privacy:metadata:preference:menubar_state'] = 'Le type d\'affichage que l\'utilisateur préfère pour la barre de menus';
$string['menubar_state_fold'] = 'Pour afficher la barre de menus comme pliée';
$string['menubar_state_unfold'] = 'Pour afficher la barre de menus comme dépliée';
$string['menubar_state_open'] = 'Pour afficher la barre de menus comme ouverte';
$string['menubar_state_hide'] = 'Pour afficher la barre de menus comme masquée';

// Profile Page.
$string['administrator'] = 'Administrator';
$string['contacts'] = 'Contacts';
$string['blogentries'] = 'Entrées de blog';
$string['discussions'] = 'Discussions';
$string['aboutme'] = 'A propos de moi';
$string['courses'] = 'Cours';
$string['interests'] = 'Centres d\'intérêt';
$string['institution'] = 'Département et institution';
$string['location'] = 'Localisation';
$string['description'] = 'Description';
$string['editprofile'] = 'Éditer le Profil';
$string['start_date'] = 'Début du cours';
$string['complete'] = 'Complété';
$string['surname'] = 'Nom';
$string['actioncouldnotbeperformed'] = 'L\'action n\'a pas pu être effectuée !';
$string['enterfirstname'] = 'Merci d\'entrer votre prénom.';
$string['enterlastname'] = 'Merci d\'entrer votre nom.';
$string['enteremailid'] = 'Merci d\'entrer votre adresse mail.';
$string['enterproperemailid'] = 'Merci d\'entrer une adresse mail existante.';
$string['detailssavedsuccessfully'] = 'Informations enregistrés avec succès!';
$string['forgotpassword'] = 'Mot de passe oublié?';

// Left Navigation Drawer.
$string['createarchivepage'] = 'Liste des cours';
$string['createanewcourse'] = 'Créer un nouveau cours';
$string['remuisettings'] = 'Paramètres RemUI';

// Right Navigation Drawer.
$string['navbartype'] = 'Couleur de la barre de navigation';
$string['sidebarcolor'] = 'Couleur de la barre latérale';
$string['sitecolor'] = 'Couleur du site';
$string['applysitewide'] = 'Appliquer sur l\'ensemble du site';
$string['applysitecolor'] = 'Appliquer la couleur du site';
$string['sidebarpinned'] = 'Barre latérale épinglée';
$string['sidebarunpinned'] = 'Barre latérale non épinglée';
$string['pinsidebar'] = 'Broche latérale';
$string['unpinsidebar'] = 'Détacher la barre latérale';

// General Settings.
$string['generalsettings' ] = 'Paramètres généraux';
$string['enableannouncement'] = "Activer l'annonce du site";
$string['enableannouncementdesc'] = "Activer une annonce à l'échelle du site pour les visiteurs / étudiants du site.";
$string['announcementtext'] = "Annonce";
$string['announcementtextdesc'] = "Message d'annonce à afficher dans tout le site.";
$string['announcementtype'] = "Type d'annonce";
$string['announcementtypedesc'] = "info / alerte / danger / succès";
$string['typeinfo'] = "Annonce d'information";
$string['typedanger'] = "Annonce urgente";
$string['typewarning'] = "Annonce d'avertissement";
$string['typesuccess'] = "Annonce de réussite";
$string['enablerecentcourses'] = 'Activer les cours récents';
$string['enablerecentcoursesdesc'] = "Si cette option est activée, le menu déroulant des cours récents s'affichera dans l'en-tête.";
$string['enableheaderbuttons'] = "Afficher les boutons d'en-tête dans la liste déroulante";
$string['enableheaderbuttonsdesc'] = "Tous les boutons affichés dans l'en-tête sont convertis en un seul menu déroulant.";
$string['mergemessagingsidebar'] = 'Panneau fusionner les messages';
$string['mergemessagingsidebardesc'] = 'Fusionner le panneau de messages dans la barre latérale droite';
$string['courseperpage'] = 'Cours par page';
$string['courseperpagedesc'] = 'Nombre de cours à afficher dur la page d\'accueil.';
$string['none'] = 'Aucun';
$string['fade'] = 'Fondu';
$string['slide-top'] = 'Glisser dessus';
$string['slide-bottom'] = 'Glisser le fond';
$string['slide-right'] = 'Glisser vers la droite';
$string['scale-up'] = 'Scale Up';
$string['scale-down'] = 'Réduire';
$string['courseanimation'] = 'Animation du cours';
$string['courseanimationdesc'] = 'Activer ceci ajoutera une animation aux cours dans la page d\'archive de cours';
$string['enablenewcoursecards'] = 'Activer les nouvelles cartes de cours';
$string['enablenewcoursecardsdesc'] = 'Activer ceci affichera les nouvelles fiches de cours sur la page d\'archives de cours';
$string['activitynextpreviousbutton'] = 'Activer le bouton Activité suivante / précédente';
$string['activitynextpreviousbuttondesc'] = 'Le bouton d\'activité suivant / précédent apparaît en haut de l\'activité pour un changement rapide';
$string['disablenextprevious'] = 'Désactiver';
$string['enablenextprevious'] = 'Activer';
$string['enablenextpreviouswithname'] = 'Activer avec le nom de l\'activité';
$string['logoorsitename'] = 'Choisissez le format de votre logo de site';
$string['logoorsitenamedesc'] = 'Vous pouvez changer le logo de l\'en tête à votre guise. <br />Les options possibles sont : Logo - Seul le logo sera affiché; <br /> Icone + Nom du site - Une icône et le nom du site seront affichés.';
$string['onlylogo'] = 'Logo seulement';
$string['iconsitename'] = 'Icône et nom du site';
$string['logo'] = 'Logo';
$string['logodesc'] = 'Vous pouvez ajouter le logo à afficher sur l\'en-tête. Noter que la hauteur préférable est de 50px. Si vous souhaitez la personnaliser, vous pouvez le faire à partir de la section Réglages généraux : CSS personnalisée.';
$string['logomini'] = 'LogoMini';
$string['logominidesc'] = "Vous pouvez ajouter le logomini à afficher dans l'en-tête lorsque la barre latérale est réduite. Remarque: la hauteur préférée est 50 px. Si vous souhaitez personnaliser, vous pouvez le faire à partir de la zone CSS personnalisée.";
$string['siteicon'] = 'Icône dans l\'en-tête';
$string['siteicondesc'] = 'Vous n\'avez de logo ? Vous pouvez choisir une icône à partir de cette <a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_new">liste</a>. <br /> Saisissez seulement le nom après le "fa-".';
$string['customcss'] = 'CSS personnalisée';
$string['customcssdesc'] = 'Les règles CSS que vous définissez ici seront ajoutées à chacune des pages, vous permettant ainsi de personnaliser facilement ce thème..';
$string['favicon'] = 'Favicon';
$string['favicondesc'] = 'Ici, Vous pouvez insérer le favicon pour votre site. <i> Pour l\'afficher tout de suite vous pouvez cocher le mode concepteur de thème.)</i>';
$string['fontselect'] = 'Sélecteur de polices';
$string['fontselectdesc'] = 'Choisissez parmi les polices standard sou les types de polices de <a href="https://fonts.google.com/" target="_new">Google</a> pour le Web. Veuillez enregistrer votre choix pour afficher les options.';
$string['fonttypestandard'] = 'Police standard ';
$string['fonttypegoogle'] = 'Police Google pour le Web';
$string['fontname'] = 'Police du site';
$string['fontnamedesc'] = 'Entrez le nom exact de la police à utiliser pour Moodle.';
$string['googleanalytics'] = 'ID de suivi Google Analytics';
$string['googleanalyticsdesc'] = 'Merci d\'entrer votre code de suivi Google Analytics pour activier le suivi des visiteurs de votre site. L\'ID de suivi s\'affiche au format UA-XXXXXXXX-X.<br />
Sachez qu\'en incluant ce paramètre, vous enverrez des données à Google Analytics et assurez-vous que vos utilisateurs en sont avertis. Notre produit ne stocke aucune des données envoyées à Google Analytics.';
$string['enablecoursestats'] = 'Activer les statistiques du cours';
$string['enablecoursestatsdesc'] = "Si cette option est activée, l'administrateur, les gestionnaires et l'enseignant verront les statistiques relatives à la page du cours.";
$string['courseeditbuttonsetting'] = "Bouton d\'édition de cours";
$string['courseeditbuttonsetting_desc'] = "Avec ce paramètre, vous pouvez ajouter un bouton sur d\'édition de à l\'en-tête du cours pour un accès plus rapide.";
$string['enabledictionary'] = 'Activer le dictionnaire';
$string['enabledictionarydesc'] = "Si activé, la fonction Dictionnaire sera activée et affichera la signification du texte sélectionné dans l'info-bulle.";
$string['more'] = 'Plus...';


// Frontpage Old String
// Home Page Settings.
$string['homepagesettings'] = 'Paramétrage de la page d\'accueil';
$string['frontpagedesign'] = 'Design Frontpage';
$string['frontpagedesigndesc'] = 'Cette section concerne le style de conception de la page d\'accueil.';
$string['frontpagechooser'] = 'Choisissez le design de la page d\'accueil';
$string['frontpagechooserdesc'] = 'Choisissez votre design de page d\'accueil.';
$string['frontpagedesignold'] = 'Ancienne conception par défaut';
$string['frontpagedesignolddesc'] = 'Tableau de bord par défaut comme ci-dessus.';
$string['frontpagedesignnew'] = 'Nouveau design';
$string['frontpagedesignnewdesc'] = 'Nouveau design avec plusieurs sections. Vous pouvez configurer les sections individuellement sur Frontpage.';
$string['newhomepagedescription'] = 'Cliquez sur "Site Home" dans la barre de navigation pour aller à "Homepage Builder" et créer votre propre page d\'accueil';
$string['frontpageloader'] = 'Télécharger l\'image du chargeur pour Frontpage';
$string['frontpageloaderdesc'] = 'Ceci remplace le chargeur par défaut avec votre image';
$string['frontpageimagecontent'] = 'Contenu de en-tête';
$string['frontpageimagecontentdesc'] = 'Cette section se rapporte à la partie supérieure de votre première page.';
$string['frontpageimagecontentstyle'] = 'Style';
$string['frontpageimagecontentstyledesc'] = 'Vous pouvez choisir en image statique ou un carrousel.';
$string['staticcontent'] = 'Statique';
$string['slidercontent'] = 'Carrousel';
$string['addtext'] = 'Ajouter du texte';
$string['defaultaddtext'] = 'La formation est une voie éprouvée pour progresser.';
$string['addtextdesc'] = 'Ici, vous pouvez ajouter le texte à afficher en première page, de préférence en HTML.';
$string['uploadimage'] = 'Télécharger une image';
$string['uploadimagedesc'] = 'Vous pouvez télécharger l\'image en tant que contenu pour la diapositive';
$string['video'] = 'Code iframe intégré';
$string['videodesc'] = ' Ici, vous pouvez insérer le code iframe intégré de la vidéo qui doit être incorporée.';
$string['contenttype'] = 'Sélectionner le type de contenu';
$string['contentdesc'] = 'Vous pouvez choisir l\'image ou donner l\'URL de vidéo.';
$string['imageorvideo'] = 'Image/ Video';
$string['image'] = 'Image';
$string['videourl'] = 'URL de la Video';
$string['slideinterval'] = 'Intervalle entre les diapositives.';
$string['slideintervalplaceholder'] = 'Nombre entier positif en millisecondes.';
$string['slideintervaldesc'] = 'Vous pouvez définir le temps de transition entre les diapositives. Dans le cas où il y a qu\'une diapositive, cette option n\'aura aucun effet.';
$string['slidercount'] = 'Nombre de diapositive';
$string['slidercountdesc'] = '';
$string['one'] = '1';
$string['two'] = '2';
$string['three'] = '3';
$string['four'] = '4';
$string['five'] = '5';
$string['eight'] = '8';
$string['twelve'] = '12';
$string['slideimage'] = 'Téléchargez des images pour le carrousel.';
$string['slideimagedesc'] = 'Vous pouvez télécharger une image en tant que contenu pour cette diapositive.';
$string['sliderurl'] = 'Ajouter un lien au bouton de la diapositive.';
$string['slidertext'] = 'Ajouter du texte à la diapositive.';
$string['defaultslidertext'] = '';
$string['slidertextdesc'] = 'Vous pouvez insérer le texte du contenu de cette diapositive. De préférence en HTML.';
$string['sliderbuttontext'] = 'Ajouter du texte sur le bouton de la diapositive.';
$string['sliderbuttontextdesc'] = 'Vous pouvez ajouter du texte sur le bouton de la diapositive.';
$string['sliderurldesc'] = 'Vous pouvez insérer le lien de la page vers laquelle l\'utilisateur sera redirigé en cliquant sur le bouton.';
$string['sliderautoplay'] = 'Définir la lecture automatique du diaporama.';
$string['sliderautoplaydesc'] = 'Sélectionner ‘oui’ Si vous voulez une transition automatique dans votre diaporama.';
$string['true'] = 'Oui';
$string['false'] = 'Non';
$string['frontpageblocks'] = 'Contenu du corps de la page d\'accueil';
$string['frontpageblocksdesc'] = 'Vous pouvez définir 4 sections markéting';
$string['frontpageblockdisplay'] = 'À propos de nous section';
$string['frontpageblockdisplaydesc'] = "Vous pouvez afficher ou masquer la section 'A propos de nous', vous pouvez également l'afficher en format de grille";
$string['donotshowaboutus'] = 'Ne pas montrer';
$string['showaboutusinrow'] = 'Afficher la section dans une rangée';
$string['showaboutusingridblock'] = 'Afficher la section dans le bloc de grille';

// About Us.
$string['frontpageaboutus'] = 'Front Page À propos de nous';
$string['frontpageaboutusdesc'] = 'Cette section présente le texte de <i>A propos de nous</i> sur la page d\'accueil.';
$string['frontpageaboutustitledesc'] = 'Ajouter un titre à la section À propos de nous';
$string['frontpageaboutusbody'] = 'Description corporelle pour la section À propos de nous';
$string['frontpageaboutusbodydesc'] = 'Une brève description de cette section';
$string['enablesectionbutton'] = 'Activer les boutons sur les sections markéting';
$string['enablesectionbuttondesc'] = 'Activer les boutons sur le corps des sections markéting.';
$string['sectionbuttontextdesc'] = 'Entrer le texte du bouton de cette section markéting.';
$string['sectionbuttonlinkdesc'] = 'Entrer l\'URL en lien avec cette section markéting.';
$string['frontpageblocksectiondesc'] = 'Ajouter un titre à cette section markéting.';
/* block section 1 */
$string['frontpageblocksection1'] = 'Titre de la 1ère section';
$string['frontpageblockdescriptionsection1'] = 'Description de la 1ère section';
$string['frontpageblockiconsection1'] = 'Icône Font-Awesome pour la 1ère section';
$string['sectionbuttontext1'] = 'Texte du bouton de la 1ère section';
$string['sectionbuttonlink1'] = 'URL du lien de la 1ère section';
/* block section 2 */
$string['frontpageblocksection2'] = 'Titre de la 2ème section';
$string['frontpageblockdescriptionsection2'] = 'Description de la 2ème section';
$string['frontpageblockiconsection2'] = 'Icône Font-Awesome pour la 2ème section';
$string['sectionbuttontext2'] = 'Texte du bouton de la 2ème section';
$string['sectionbuttonlink2'] = 'URL du lien de la 2ème section';
/* block section 3 */
$string['frontpageblocksection3'] = 'Titre de la 3ème section';
$string['frontpageblockdescriptionsection3'] = 'Description de la 3ème section';
$string['frontpageblockiconsection3'] = 'Icône Font-Awesome pour la 3ème section';
$string['sectionbuttontext3'] = 'Texte du bouton de la 3ème section';
$string['sectionbuttonlink3'] = 'URL du lien de la 3ème section';
/* block section 4 */
$string['frontpageblocksection4'] = 'Titre de la 4ème section';
$string['frontpageblockdescriptionsection4'] = 'Description de la 4ème section';
$string['frontpageblockiconsection4'] = 'Icône Font-Awesome pour la 4ème section';
$string['sectionbuttontext4'] = 'Texte du bouton de la 4ème section';
$string['sectionbuttonlink4'] = 'URL du lien de la 4ème section';
$string['defaultdescriptionsection'] = 'Approfondir de façon holistique les technologies \'just in time\' via des scénarios d\'entreprise.';
$string['frontpageaboutus'] = 'Front Page À propos de nous';
$string['frontpageaboutusdesc'] = 'Cette section présente le texte de <i>A propos de nous</i> sur la page d\'accueil.';
$string['enablefrontpageaboutus'] = 'Afficher la section <i>A propos de nous</i> ';
$string['enablefrontpageaboutusdesc'] = 'Afficher la section <i>A propos de nous</i> sur la page d\'accueil.';
$string['frontpageaboutusheading'] = 'En tête de <i>A propos de nous</i>';
$string['frontpageaboutusheadingdesc'] = 'Titre en en-tête pour la section <i>A propos de nous</i>.';
$string['frontpageaboutustext'] = 'Texte de <i>A propos de nous</i>';
$string['frontpageaboutustextdesc'] = 'Entrer le texte  de <i>A propos de nous</i> sur la page d\'accueil.';
$string['frontpageaboutusdefault'] = '<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
              Ut enim ad minim veniam.</p>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                  eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.Lorem ipsum dolor sit amet,
                  consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                  labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur
                  adipisicing elit, sed do eiusmod tempor incididunt
                  ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>';
$string['testimonialcount'] = 'Compte Témoignage';
$string['testimonialcountdesc'] = 'Nombre de témoignages à montrer.';
$string['testimonialimage'] = 'Image de témoignage';
$string['testimonialimagedesc'] = 'Image de personne à afficher avec témoignage';
$string['testimonialname'] = "Nom d'une personne";
$string['testimonialnamedesc'] = 'Nom de personne';
$string['testimonialdesignation'] = 'Désignation de la personne';
$string['testimonialdesignationdesc'] = 'Désignation de la personne.';
$string['testimonialtext'] = 'Témoignage de la personne';
$string['testimonialtextdesc'] = 'Quelle personne dit';
$string['frontpageblockimage'] = 'Télécharger une image';
$string['frontpageblockimagedesc'] = 'Vous pouvez télécharger une image en tant que contenu pour cela.';
$string['frontpageblockiconsectiondesc'] = 'Vous pouvez choisir une icône à partir de cette <a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_new">liste</a>. <br /> Saisissez seulement le nom après le "fa-". ';
$string['frontpageblockdescriptionsectiondesc'] = 'Une courte description.';


// Footer Page Settings.
$string['footersettings'] = 'Paramétrages du pied de page';
$string['socialmedia'] = 'Paramètres des réseaux sociaux ';
$string['socialmediadesc'] = 'Entrer les liens des réseaux sociaux pour votre site.';
$string['facebooksetting'] = 'Paramétrage pour Facebook';
$string['facebooksettingdesc'] = 'Enter le lien de votre page Facebook. Par exemple https://www.facebook.com/pagename';
$string['twittersetting'] = 'Paramétrage pour Twitter';
$string['twittersettingdesc'] = 'Enter le lien de votre Twitter. Par exemple https://www.twitter.com/pagename';
$string['linkedinsetting'] = 'Paramétrage pour Linkedin';
$string['linkedinsettingdesc'] = 'Enter le lien de votre page Linkedin. Par exemple https://www.linkedin.com/in/pagename';
$string['gplussetting'] = 'Paramétrage pour Google Plus';
$string['gplussettingdesc'] = 'Enter le lien de votre page Google Plus. Par exemple https://plus.google.com/pagename';
$string['youtubesetting'] = 'Paramétrage pour YouTube';
$string['youtubesettingdesc'] = 'Enter le lien de votre page YouTube. Par exemple https://www.youtube.com/channel/UCU1u6QtAAPJrV0v0_c2EISA';
$string['instagramsetting'] = 'Paramétrage pour Instagram';
$string['instagramsettingdesc'] = 'Enter le lien de votre page Instagram. Par exemple https://www.linkedin.com/company/name';
$string['pinterestsetting'] = 'Paramétrage pour Pinterest';
$string['pinterestsettingdesc'] = 'Enter le lien de votre page Pinterest. Par exemple https://www.pinterest.com/name';
$string['quorasetting'] = 'Paramétrage pour quora';
$string['quorasettingdesc'] = 'Enter le lien de votre page quora. Par exemple https://www.quora.com/name';
$string['footerbottomtext'] = 'Texte en bas à gauche du pied de page';
$string['footerbottomlink'] = 'Lien en bas à gauche Du pied de page';
$string['footerbottomlinkdesc'] = 'Entrez le lien en bas à gauche du pied de page. Par exemple http://www.monblog.fr';
$string['footercolumn1heading'] = 'Contenu de la 1ère colonne, à gauche, du pied de page.';
$string['footercolumn1headingdesc'] = 'Cette section se rapporte à la partie inférieure (colonne 1) de votre page d\'accueil.';
$string['footercolumn1title'] = 'Titre de la colonne 1 du pied de page';
$string['footercolumn1titledesc'] = 'Ajouter le titre de cette colonne.';
$string['footercolumn1customhtml'] = 'HTML personnalisé';
$string['footercolumn1customhtmldesc'] = 'Vous pouvez personnaliser le HTML de cette colonne en utilisant la zone de texte ci-dessus.';
$string['footercolumn2heading'] = 'Contenu de la 2ère colonne, au milieu, du pied de page.';
$string['footercolumn2headingdesc'] = 'Cette section se rapporte à la partie inférieure (colonne 2) de votre page d\'accueil.';
$string['footercolumn2title'] = 'Titre de la colonne 2 du pied de page';
$string['footercolumn2titledesc'] = 'Ajouter le titre de cette colonne.';
$string['footercolumn2customhtml'] = 'HTML personnalisé';
$string['footercolumn2customhtmldesc'] = 'Vous pouvez personnaliser le HTML de cette colonne en utilisant la zone de texte ci-dessus.';
$string['footercolumn3heading'] = 'Contenu de la 3ère colonne, à droite, du pied de page.';
$string['footercolumn3headingdesc'] = 'Cette section se rapporte à la partie inférieure (colonne 3) de votre page d\'accueil.';
$string['footercolumn3title'] = 'Titre de la colonne 3 du pied de page';
$string['footercolumn3titledesc'] = 'Ajouter le titre de cette colonne.';
$string['footercolumn3customhtml'] = 'HTML personnalisé';
$string['footercolumn3customhtmldesc'] = 'Vous pouvez personnaliser le HTML de cette colonne en utilisant la zone de texte ci-dessus.';
$string['footerbottomheading'] = 'Paramétrage de la zone basse du pied de page.';
$string['footerbottomdesc'] = 'Ici vous pouvez spécifier un lien que vous voulez afficher dans la section inférieure de pied de page';
$string['footerbottomtextdesc'] = 'Ajouter le texte en bas à droite du pied de page.';
$string['poweredbyedwiser'] = 'Élaboré par @jmd87fr en collaboration avec Edwiser';
$string['poweredbyedwiserdesc'] = 'Désélectionnez pour supprimer \'Élaboré par @jmd87fr en collaboration avec Edwiser\' de votre site.';

// Login Page Settings.
$string['loginsettings'] = 'Paramétrages de la page de connexion.';
$string['navlogin_popup'] = 'Activer le menu de connexion dans la barre de navigation';
$string['navlogin_popupdesc'] = 'Activer le menu de connexion dans la barre de navigation dans l\'en-tête.';
$string['loginsettingpic'] = 'Télécharger l\'image de fond';
$string['loginsettingpicdesc'] = 'Télécharger une image comme de fon de la page avec le formulaire de connexion.';
$string['signuptextcolor'] = 'Couleur du texte du panneau d\'inscription';
$string['signuptextcolordesc'] = 'Sélectionnez la couleur du texte pour le panneau d\'inscription.';
$string['left'] = "Côté gauche";
$string['right'] = "Côté droit";
$string['remember_me'] = "Remember Me";
$string['brandlogopos'] = "Position du logo marque";
$string['brandlogoposdesc'] = "Si activé, le logo de la marque sera visible dans la barre latérale droite au-dessus du formulaire de connexion.";
$string['brandlogotext'] = "Description du site";
$string['brandlogotextdesc'] = "Ajoutez du texte pour la description du site qui s'affichera sur la page de connexion et d'inscription. Gardez ce champ vide si vous ne voulez pas mettre de description.";
$string['loginpagelayout'] = 'Mise en page de connexion';
$string['loginpagelayoutdesc'] = '';
$string['centerlayout'] = 'Mise en page centrée';
$string['overlaylayout'] = 'Superposition droite';

// License Settings.
$string['licensenotactive'] = '<strong>Attention !</strong> La licence n\'est pas activée, <strong>merci de l\'activer</strong> dans les réglages du thème RemUI.';
$string['licensenotactiveadmin'] = '<strong>Attention !</strong> La licence n\'est pas activée, <strong>merci de l\'activer</strong> <a href="'.$CFG->wwwroot.'/admin/settings.php?section=themesettingremui#remuilicensestatus" ><strong> ICI</strong></a>.';
$string['activatelicense'] = 'Activer la licence';
$string['deactivatelicense'] = 'Désactiver la licence';
$string['renewlicense'] = 'Renouveler la licence';
$string['active'] = 'Activée';
$string['notactive'] = 'N\'est pas activée';
$string['expired'] = 'Expirée';
$string['licensekey'] = 'Clef de licence';
$string['licensestatus'] = 'Status de la licence';
$string['noresponsereceived'] = 'Aucune réponse reçue du serveur. Veuillez réessayer plus tard.';
$string['licensekeydeactivated'] = 'La clef de la licence est désactivée.';
$string['siteinactive'] = 'Site inactif (Appuyez sur <i>Activer la licence</i> pour activer le thème).';
$string['entervalidlicensekey'] = 'Merci d\'entrer une clef de licence valide.';
$string['licensekeyisdisabled'] = 'Votre clef de licence est désactivée.';
$string['licensekeyhasexpired'] = "Votre clef de licence est expirée. Veuillez la renouveler.";
$string['licensekeyactivated'] = "Votre clef de licence est activée.";
$string['enterlicensekey'] = "Merci de d'entrer votre clef de licence.";
$string['edwiserremuilicenseactivation'] = 'Activation de la licence Edwiser RemUI ';
$string['nolicenselimitleft'] = "Limite d'activation maximale atteinte, aucune activation restante.";

// News And Updates Page.
$string['newsandupdates'] = 'Nouvelles mise à jour';
$string['newupdatemessage'] = 'Nouvelle mise à jour disponible pour RemUI.';
$string['currentversionmessage'] = 'Votre version actuelle est';
$string['downloadupdate'] = 'Télécharger la mise à jour';
$string['latestversionmessage'] = 'Vous utilisez la dernière version de RemUI.';
$string['rateremui'] = 'Noter RemUI';
$string['fullname']  = 'Nom complet';
$string['providefeedback'] = "S'il vous plaît fournir vos commentaires sur RemUI";
$string['sendfeedback'] = 'Envoyer des commentaires';
$string['recentnews'] = 'Nouvelles récentes';

// About Edwiser RemUI.
$string['aboutsettings'] = "À propos d'Edwiser RemUI";
$string['notenrolledanycourse'] = 'Non inscrit à aucun cours';

/* My Course Page */
$string['resume'] = 'Continuer';
$string['start'] = 'Commencer';
$string['completed'] = 'Terminé';

// Course.
$string['graderreport'] = 'Rapport d’niveleuse';
$string['enroluser'] = 'Inscrire des utilisateurs';
$string['activityeport'] = 'Rapport d’activité';
$string['editcourse'] = 'Éditer les cours';
$string['sections'] = "Sections";

// Next Previous Activity.
$string['activityprev'] = 'Activité précédente';
$string['activitynext'] = 'Activité suivante';

// Login Page.
$string['signin'] = 'Connexion';
$string['signup'] = "S'inscrire";
$string['noaccount'] = 'Pas de compte?';

// Incourse Page.
$string['backtocourse'] = 'Aperçu du cours';

// Header Section.
$string['togglefullscreen'] = 'Passer en plein écran';
$string['recent'] = 'Récent';

// Course Stats.
$string['enrolledusers'] = 'Étudiants <br>inscrits';
$string['studentcompleted'] = 'Étudiants <br>terminés';
$string['inprogress'] = 'En <br>cours';
$string['yettostart'] = 'Mais <br>pour commencer';

// Footer Content.
$string['followus'] = 'Suivez nous';
$string['poweredby'] = 'Powered by Edwiser RemUI';

// Course Archive Page.
$string['mycourses'] = "Mes cours";
$string['allcategories'] = 'Toutes les catégories';
$string['categorysort'] = 'Trier les catégories';
$string['sortdefault'] = 'Trier (aucun)';
$string['sortascending'] = 'Trier de A à Z';
$string['sortdescending'] = 'Trier de Z à A';

// Dashboard Blocks.
$string['viewcourse'] = "VUE COURS";
$string['searchcourses'] = "Rechercher des cours";

$string['hiddencourse'] = 'Cours caché';

// Usage tracking
$string['enableusagetracking'] = "Activer le trakcing d'utilisation";

$string['enableusagetrackingdesc'] = "<strong>AVIS DE SUIVI DE L'UTILISATION</strong>

<hr class='text-muted' />

<p>Edwiser collectera désormais des données anonymes pour générer des statistiques d'utilisation des produits.</p>

<p>Ces informations nous aideront à guider le développement dans la bonne direction et à faire prospérer la communauté Edwiser.</p>

<p>Cela dit, nous ne collectons pas vos données personnelles ni celles de vos étudiants au cours de ce processus. Vous pouvez désactiver cela à partir du plugin chaque fois que vous souhaitez vous désinscrire de ce service.</p>

<p>Un aperçu des données collectées est disponible <strong><a href='https://forums.edwiser.org/topic/67/anonymously-tracking-the-usage-of-edwiser-products' target='_blank'>ici</a></strong>.</p>";