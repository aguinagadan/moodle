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

$string['advancedsettings'] = 'Ajustes avanzados';
$string['backgroundimage'] = 'Imagen de fondo';
$string['backgroundimage_desc'] = 'La imagen para mostrar como fondo del sitio. La imagen de fondo que cargue aquí anulará la imagen de fondo en sus archivos preestablecidos de tema.';
$string['brandcolor'] = 'Color de la marca';
$string['brandcolor_desc'] = 'El color de acento.';
$string['bootswatch'] = 'Bootswatch';
$string['bootswatch_desc'] = 'Un bootswatch es un conjunto de variables Bootstrap y css para diseñar Bootstrap';
$string['choosereadme'] = '
<div class="about-remui-wrapper text-center">
    <div class="about-remui m-auto" style="max-width: 1000px;">
        <h1 class="text-center">Bienvenido a Edwiser RemUI</h1><br>
        <h4 class="text-muted">
        Edwiser RemUI es la nueva revolución en experiencia de Moodle. Ha sido diseñado para mejorar el aprendizaje con
		diseños personalizados y navegación simplificada.<br><br>
        Estamos seguros que te gustara el look remodelado!
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
                <a href="https://edwiser.org/remui/documentation/" target="_blank" class="btn btn-primary btn-round">Documentación</a>&nbsp;
              </div>
              <div class="btn-group" role="group">
                <a href="https://edwiser.org/contact-us/" target="_blank" class="btn btn-primary btn-round">Soporte</a>
              </div>
            </div>
        </div>
        <br>
        <h1 class="text-center">Personalice Su Tema</h1>
        <h4 class="text-muted text-center">
			Sabemos que ningún LMS es igual. Trabajaremos con usted para comprender sus necesidades, y diseñar y desarrollar una solución que satisfaga sus objetivos.
        </h4>
        <br><br>
        <div class="row wdm_generalbox">
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-cogs"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Personalización del Tema</h4>
                </div>
            </div>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Diseño de Funcionalidad</h4>
                </div>
            </div>
            <br>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-link"></i>
                </div>
                <div class="iconbox-content">
                    <h4>API Integración</h4>
                </div>
            </div>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-life-ring"></i>
                </div>
                <div class="iconbox-content">
                    <h4>LMS Consultoría</h4>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="text-center">
            <a class="btn btn-primary btn-lg" target="_blank" href="https://edwiser.org/contact-us/">Contactarnos</a>&nbsp;&nbsp;
        </div>
    </div>
</div>
<br />';
$string['aboutremui'] = 'Acerca de Edwiser RemUI';
$string['currentinparentheses'] = '(actual)';
$string['configtitle'] = 'Edwiser RemUI';
$string['fontsize'] = 'Tamaño de fuente base del tema';
$string['fontsize_desc'] = 'Ingrese un tamaño de fuente en%';
$string['nobootswatch'] = 'Ninguno';
$string['pluginname'] = 'Edwiser RemUI';
$string['presetfiles'] = 'Archivos preestablecidos de temas adicionales';
$string['presetfiles_desc'] = 'Los archivos preestablecidos se pueden usar para alterar dramáticamente la apariencia del tema';
$string['preset'] = 'Tema preestablecido';
$string['preset_desc'] = 'Elija un ajuste preestablecido para cambiar ampliamente el aspecto del tema.';
$string['privacy:metadata'] = 'El tema remui no almacena ningún dato personal sobre ningún usuario.';
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Use este campo para proporcionar código SCSS o CSS que se inyectará al final de la hoja de estilo.';
$string['rawscsspre'] = 'SCSS inicial sin procesar';
$string['rawscsspre_desc'] = 'En este campo puede proporcionar el código SCSS de inicialización, se inyectará antes que todo lo demás. La mayoría de las veces usará esta configuración para definir variables.';
$string['region-side-pre'] = 'Derecha';
$string['privacy:metadata:preference:draweropennav'] = 'La preferencia del usuario para ocultar o mostrar la navegación del menú del cajón.';
$string['privacy:drawernavclosed'] = 'La preferencia actual para el cajón de navegación está cerrada.';
$string['privacy:drawernavopen'] = 'La preferencia actual para el cajón de navegación está abierta.';
$string['privacy:metadata:preference:course_view_state'] = 'El tipo de pantalla que el usuario prefiere para la lista de cursos';
$string['course_view_state_description_grid'] = 'Para mostrar los cursos en formato de cuadrícula';
$string['course_view_state_description_list'] = 'Para mostrar los cursos en formato de lista';

/* Course view preference */
$string['privacy:metadata:preference:viewCourseCategory'] = 'El tipo de pantalla que el usuario prefiere para la lista de cursos';
$string['viewCourseCategory_grid'] = 'Para mostrar los cursos en formato de cuadrícula';
$string['viewCourseCategory_list'] = 'Para mostrar los cursos en formato de lista';

/* Aside right view preference */
$string['privacy:metadata:preference:aside_right_state'] = 'Si el bloque a un lado de la derecha debe mantenerse abierto o cerrado';
$string['aside_right_state_'] = 'Para mostrar el bloque a un lado a la derecha como abierto'; // blank value
$string['aside_right_state_overrideaside'] = 'Para mostrar el bloque a un lado a la derecha como cerrado'; // overrideaside

/* Menu view preference */
$string['privacy:metadata:preference:menubar_state'] = 'El tipo de pantalla que el usuario prefiere para la barra de menú';
$string['menubar_state_fold'] = 'Para mostrar la barra de menú como doblada';
$string['menubar_state_unfold'] = 'Para mostrar la barra de menú como desplegada';
$string['menubar_state_open'] = 'Para mostrar la barra de menú como abierta';
$string['menubar_state_hide'] = 'Para mostrar la barra de menú como oculta';

// Profile Page.
$string['administrator'] = 'Administrador';
$string['contacts'] = 'Contactos';
$string['blogentries'] = 'Entradas de Blog';
$string['discussions'] = 'Discusiones';
$string['aboutme'] = 'Acerca de Mi';
$string['courses'] = 'Cursos';
$string['interests'] = 'Intereses';
$string['institution'] = 'Departamento e Institución';
$string['location'] = 'Lugar';
$string['description'] = 'Descripción';
$string['editprofile'] = 'Editar Perfil';
$string['start_date'] = 'Fecha de inicio';
$string['complete'] = 'Completado';
$string['surname'] = 'Apellido';
$string['actioncouldnotbeperformed'] = 'No se pudo ejecutar la accion!';
$string['enterfirstname'] = 'Por favor, ingrese su nombre.';
$string['enterlastname'] = 'Por favor, ingrese su apellido.';
$string['enteremailid'] = 'Ingrese su identificación de EMAIL.';
$string['enterproperemailid'] = 'Por favor, ingrese una dirección de EMAIL correcta.';
$string['detailssavedsuccessfully'] = 'Detalles Guardados Correctamente!';
$string['forgotpassword'] = 'Contraseña olvidada?';

// Left Navigation Drawer.
$string['createarchivepage'] = 'Página de archivo del curso';
$string['createanewcourse'] = 'Crear un curso nuevo';
$string['remuisettings'] = 'Configuración de RemUI';

// Right Navigation Drawer.
$string['navbartype'] = 'Color de la barra de navegación';
$string['sidebarcolor'] = 'Color de la barra lateral';
$string['sitecolor'] = 'Color del sitio';
$string['applysitewide'] = 'Aplicar en todo el sitio';
$string['applysitecolor'] = 'Aplicar Color del Sitio';
$string['sidebarpinned'] = 'Barra lateral fijada';
$string['sidebarunpinned'] = 'Barra lateral sin fijar';
$string['pinsidebar'] = 'Pin barra lateral';
$string['unpinsidebar'] = 'Desanclar barra lateral';

// General Settings.
$string['generalsettings' ] = 'Configuración General';
$string['enableannouncement'] = "Activar avisos del sitio";
$string['enableannouncementdesc'] = "Activar avisos en todo el sitio para visitantes/alumnos.";
$string['announcementtext'] = "AVISO";
$string['announcementtextdesc'] = "Mensaje de aviso para mostrar en todo el sitio.";
$string['announcementtype'] = "Tipo de aviso";
$string['announcementtypedesc'] = "información / alerta / peligro / éxito";
$string['typeinfo'] = "Aviso de información";
$string['typedanger'] = "Aviso urgente";
$string['typewarning'] = "Aviso de advertencia";
$string['typesuccess'] = "Aviso de éxito";
$string['enablerecentcourses'] = 'Habilitar cursos recientes';
$string['enablerecentcoursesdesc'] = 'Si está habilitado, el menú desplegable de los cursos recientes se mostrará en el encabezado.';
$string['enableheaderbuttons'] = 'Mostrar botones de encabezado en el menú desplegable';
$string['enableheaderbuttonsdesc'] = 'Todos los botones que se muestran en el encabezado se convierten en un solo menú desplegable.';
$string['mergemessagingsidebar'] = 'Panel de mensajes de combinación';
$string['mergemessagingsidebardesc'] = 'Fusionar el panel de mensajes en la barra lateral derecha';
$string['courseperpage'] = 'Cursos por Página';
$string['courseperpagedesc'] = 'Numero de cursos a mostrar por página en la página de archivo de cursos.';
$string['none'] = 'Ninguna';
$string['fade'] = 'Descolorarse';
$string['slide-top'] = 'Deslice la parte superior';
$string['slide-bottom'] = 'Parte inferior de la diapositiva';
$string['slide-right'] = 'Deslice a la derecha';
$string['scale-up'] = 'Aumentar proporcionalmente';
$string['scale-down'] = 'Reducir proporcionalmente';
$string['courseanimation'] = 'Curso de animacion';
$string['courseanimationdesc'] = 'Al habilitar esto, se agregará animación a los cursos en la página Archivo del curso.';
$string['enablenewcoursecards'] = 'Habilitar nuevas tarjetas de curso';
$string['enablenewcoursecardsdesc'] = 'Al habilitar esto, se mostrarán las nuevas tarjetas del curso en la página Archivo del curso.';
$string['activitynextpreviousbutton'] = 'Habilitar el botón de actividad Siguiente / Anterior';
$string['activitynextpreviousbuttondesc'] = 'El botón de actividad siguiente / anterior aparece en la parte superior de la actividad para un cambio rápido';
$string['disablenextprevious'] = 'Inhabilitar';
$string['enablenextprevious'] = 'Habilitar';
$string['enablenextpreviouswithname'] = 'Activar con el nombre de la actividad';
$string['logoorsitename'] = 'Elegir el formato del logo';
$string['logoorsitenamedesc'] = 'Usted puede cambiar el logo del sitio las veces que quiera. <br />Las opciones disponibles son: Logo - Sólo se mostrará el logo; <br /> Icon+sitename - Se mostrará el icono y el nombre del sitio.';
$string['onlylogo'] = 'Únicamente el logo';
$string['iconsitename'] = 'Icono y nombre del sitio';
$string['logo'] = 'Logo';
$string['logodesc'] = 'Usted puede agregar el logo que se mostrara en la cabecera. Nota - El alto preferido es 50px. Si desea personalizarlo, Puede hacerlo desde la caja CSS.';
$string['logomini'] = 'LogoMini';
$string['logominidesc'] = 'Puede agregar el logomini que se mostrará en el encabezado cuando la barra lateral esté contraída. Nota- La altura preferida es 50px. En caso de que desee personalizar, puede hacerlo desde el cuadro personalizado de CSS.';
$string['siteicon'] = 'Icono del sitio';
$string['siteicondesc'] = 'Si no tiene un icono puede elegir uno de <a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_new">list</a>. <br /> Solo ingrese el texto que aparece después de "fa-".';
$string['customcss'] = 'Personalizar CSS';
$string['customcssdesc'] = 'Usted puede personalizar las CSS Desde la caja de texto superior. Los cambios se reflejarán en todas las páginas de su sitio.';
$string['favicon'] = 'Favicon';
$string['favicondesc'] = 'El favicon de su sitio. Inserte aquí el icono de su sitio.';
$string['fontselect'] = 'Selector del Tipo de Fuente';
$string['fontselectdesc'] = 'Selecciones fuentes estándar o <a href="https://fonts.google.com/" target="_new">fuentes Google</a>. Por favor guarde los cambios antes de mostrar su selección.';
$string['fonttypestandard'] = 'Fuente Estándar';
$string['fonttypegoogle'] = 'Fuente Google';
$string['fontname'] = 'Tipografía del sitio';
$string['fontnamedesc'] = 'Seleccione el nombre exacto de la tipografía para usar en Moodle.';
$string['googleanalytics'] = 'ID de seguimiento de Google Analytics';
$string['googleanalyticsdesc'] = 'Ingresa tu ID de seguimiento de Google Analytics para habilitar los análisis en tu sitio web. El formato de ID de seguimiento debe ser como [UA-XXXXX-Y].<br />
Tenga en cuenta que al incluir esta configuración, estará enviando datos a Google Analytics y debe asegurarse de que sus usuarios estén advertidos al respecto. Nuestro producto no almacena ninguno de los datos que se envían a Google Analytics.';
$string['enablecoursestats'] = 'Habilitar estadísticas del curso';
$string['enablecoursestatsdesc'] = 'Si está habilitado, el Administrador, los Gerentes y el maestro verán las estadísticas relacionadas con el curso en la página del curso.';
$string['courseeditbuttonsetting'] = 'Botón de edición del curso';
$string['courseeditbuttonsetting_desc'] = 'Con esta configuración, puede agregar un botón de encendido / apagado de edición de curso adicional al encabezado del curso para una accesibilidad más rápida.';
$string['enabledictionary'] = 'Habilitar diccionario';
$string['enabledictionarydesc'] = 'Si está habilitado, la función Diccionario se activará y mostrará el significado del texto seleccionado.';
$string['more'] = 'Más...';


// Frontpage Old String
// Home Page Settings.
$string['homepagesettings'] = 'ParamÃ©trage de la page d\'accueil';
$string['frontpagedesign'] = 'Diseño de portada';
$string['frontpagedesigndesc'] = 'Esta sección se relaciona con el estilo de diseño de la portada.';
$string['frontpagechooser'] = 'Elija diseño de portada';
$string['frontpagechooserdesc'] = 'Elija su diseño de portada.';
$string['frontpagedesignold'] = 'Diseño antiguo predeterminado';
$string['frontpagedesignolddesc'] = 'Panel predeterminado como el anterior.';
$string['frontpagedesignnew'] = 'Nuevo diseño';
$string['frontpagedesignnewdesc'] = 'Nuevo diseño fresco con múltiples secciones. Puede configurar las secciones individualmente en la portada.';
$string['newhomepagedescription'] = 'Haga clic en Inicio del sitio en la barra de navegación para ir a la página principal y crear su propia página de inicio';
$string['frontpageloader'] = 'Subir imagen del cargador para portada';
$string['frontpageloaderdesc'] = 'Esto reemplaza el cargador por defecto con su imagen';
$string['frontpageimagecontent'] = 'Contenido de la cabecera';
$string['frontpageimagecontentdesc'] = ' Esta sección pertenece a la parte superior de la página principal.';
$string['frontpageimagecontentstyle'] = 'Estilo';
$string['frontpageimagecontentstyledesc'] = 'Usted puede escoger entre Static & Slider.';
$string['staticcontent'] = 'Estático';
$string['slidercontent'] = 'Slider';
$string['addtext'] = 'Agregar texto';
$string['defaultaddtext'] = 'La educación es el camino comprobado hacia el progreso.';
$string['addtextdesc'] = 'Desde aquí usted puede agregar el texto que se mostrará en la página principal, de preferencia en HTML.';
$string['uploadimage'] = 'Cargar imagen';
$string['uploadimagedesc'] = 'Usted puede cargar una imagen como contenido para la diapositiva';
$string['video'] = 'iframe Embedded code';
$string['videodesc'] = ' Aquí usted puede insertar el código iframe Embedded del vídeo que desea insertar.';
$string['contenttype'] = 'Seleccione tipo de contenido';
$string['contentdesc'] = 'Usted puede escoger una imagen o insertar una dirección url de vídeo.';
$string['imageorvideo'] = 'Imagen / Video';
$string['image'] = 'Imagen';
$string['videourl'] = 'Video URL';
$string['slideinterval'] = 'Intervalo de diapositiva';
$string['slideintervalplaceholder'] = 'Número entero positivo en milisegundos.';
$string['slideintervaldesc'] = 'Usted puede establecer la transición de tiempo entre diapositivas. Si sólo hay una diapositiva esta opción no tendrá efecto.';
$string['slidercount'] = 'Número de diapositivas';
$string['slidercountdesc'] = '';
$string['one'] = '1';
$string['two'] = '2';
$string['three'] = '3';
$string['four'] = '4';
$string['five'] = '5';
$string['eight'] = '8';
$string['twelve'] = '12';
$string['slideimage'] = 'Cargar imágenes para diapositiva';
$string['slideimagedesc'] = 'Usted puede cargar una imagen como contenido para esta diapositiva.';
$string['sliderurl'] = 'Agregar enlace para el botón de la diapositiva';
$string['slidertext'] = 'Agregar texto de diapositiva';
$string['defaultslidertext'] = '';
$string['slidertextdesc'] = 'Usted puede insertar contenido de texto en este lugar, de preferencia en HTML.';
$string['sliderbuttontext'] = 'Agregar texto para el botón en la diapositiva';
$string['sliderbuttontextdesc'] = 'Usted puede agregar texto al botón en esta diapositiva.';
$string['sliderurldesc'] = 'Inserte el enlace de la página hacia donde será dirigido después de hacer clic en el botón.';
$string['sliderautoplay'] = 'Establecer Slider en Autoplay';
$string['sliderautoplaydesc'] = 'Seleccione ‘Si’ si quiere una transición automática en el show de diapositivas.';
$string['true'] = 'Si';
$string['false'] = 'No';
$string['frontpageblocks'] = 'Contenido del cuerpo';
$string['frontpageblocksdesc'] = 'Usted puede insertar un título para el cuerpo de su sitio.';
$string['frontpageblockdisplay'] = 'Sección Sobre nosotros';
$string['frontpageblockdisplaydesc'] = 'Puede mostrar u ocultar la sección "Acerca de nosotros", también puede mostrarla en formato de cuadrícula';
$string['donotshowaboutus'] = 'No mostrar';
$string['showaboutusinrow'] = 'Mostrar sección en una fila';
$string['showaboutusingridblock'] = 'Mostrar sección en bloque de cuadrícula';

// About Us.
$string['frontpageaboutus'] = 'Página principal Sobre nosotros';
$string['frontpageaboutusdesc'] = 'Esta sección es para la página "sobre nosotros"';
$string['frontpageaboutustitledesc'] = 'Agregar título a la sección Acerca de nosotros';
$string['frontpageaboutusbody'] = 'Descripción del cuerpo de la sección Acerca de nosotros';
$string['frontpageaboutusbodydesc'] = 'Una breve descripción sobre esta sección';
$string['enablesectionbutton'] = 'Activar botones en secciones';
$string['enablesectionbuttondesc'] = 'Activar botones en las secciones de página.';
$string['sectionbuttontextdesc'] = 'Ingrese el texto para el botón en esta sección.';
$string['sectionbuttonlinkdesc'] = 'Ingrese la URL Para esta sección.';
$string['frontpageblocksectiondesc'] = 'Agregar un título a esta sección.';
/* block section 1 */
$string['frontpageblocksection1'] = 'Título del texto para la primera sección';
$string['frontpageblockdescriptionsection1'] = 'Descripción de la primera sección';
$string['frontpageblockiconsection1'] = 'Icono de Font-Awesome para la primera sección';
$string['sectionbuttontext1'] = 'Texto del botón para la primera sección';
$string['sectionbuttonlink1'] = 'URL link Para la primera sección';
/* block section 2 */
$string['frontpageblocksection2'] = 'Título para la segunda sección';
$string['frontpageblockdescriptionsection2'] = 'Descripción para la segunda sección';
$string['frontpageblockiconsection2'] = 'Icono Font-Awesome para la segunda sección';
$string['sectionbuttontext2'] = 'Texto del botón Para la segunda sección';
$string['sectionbuttonlink2'] = 'URL link Para la segunda sección';
/* block section 3 */
$string['frontpageblocksection3'] = 'Título para la tercera sección';
$string['frontpageblockdescriptionsection3'] = 'Descripción para la tercera sección';
$string['frontpageblockiconsection3'] = 'Icono Font-Awesome para la tercera sección';
$string['sectionbuttontext3'] = 'Texto del botón Para la tercera sección';
$string['sectionbuttonlink3'] = 'URL link Para la tercera sección';
/* block section 4 */
$string['frontpageblocksection4'] = 'Título para la cuarta sección';
$string['frontpageblockdescriptionsection4'] = 'Descripción para la cuarta sección';
$string['frontpageblockiconsection4'] = 'Icono Font-Awesome para la cuarta sección';
$string['sectionbuttontext4'] = 'Texto del botón para la cuarta sección';
$string['sectionbuttonlink4'] = 'URL link Para la cuarta sección';
$string['defaultdescriptionsection'] = 'Tecnologías a tiempo para escenarios corporativos.';
$string['frontpageaboutus'] = 'Página principal Sobre nosotros';
$string['frontpageaboutusdesc'] = 'Esta sección es para la página "sobre nosotros"';
$string['enablefrontpageaboutus'] = 'Activar la sección sobre nosotros';
$string['enablefrontpageaboutusdesc'] = 'Activar la sección sobre nosotros en la página principal.';
$string['frontpageaboutusheading'] = 'Título sobre nosotros';
$string['frontpageaboutusheadingdesc'] = 'Cabecera de texto de esta sección';
$string['frontpageaboutustext'] = 'Texto sobre nosotros';
$string['frontpageaboutustextdesc'] = 'Ingresar el texto sobre nosotros.';
$string['frontpageaboutusdefault'] = '<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
              Ut enim ad minim veniam.</p>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                  eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.Lorem ipsum dolor sit amet,
                  consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                  labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur
                  adipisicing elit, sed do eiusmod tempor incididunt
                  ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>';
$string['testimonialcount'] = 'Recuento de testimonios';
$string['testimonialcountdesc'] = 'Numero de testiomios para mostrar.';
$string['testimonialimage'] = 'Imagen para testimonios';
$string['testimonialimagedesc'] = 'Imagen de la persona para mostrar con testimonios';
$string['testimonialname'] = 'Nombre de la persona';
$string['testimonialnamedesc'] = 'Nombre de la persona';
$string['testimonialdesignation'] = 'Designación de la persona';
$string['testimonialdesignationdesc'] = 'Designación de la persona\s.';
$string['testimonialtext'] = 'Testimonios de las persona\s';
$string['testimonialtextdesc'] = 'Qué dice la persona';
$string['frontpageblockimage'] = 'Cargar imagen';
$string['frontpageblockimagedesc'] = 'Puede cargar una imagen como contenido para esto.';
$string['frontpageblockiconsectiondesc'] = 'Usted puede elegir cualquier icono desde aquí <a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_new">list</a>. Sólo ingrese el texto después de "fa-". ';
$string['frontpageblockdescriptionsectiondesc'] = 'Una descripción corta sobre el título.';


// Footer Page Settings.
$string['footersettings'] = 'Configuración del pie de página';
$string['socialmedia'] = 'Configuración de redes sociales';
$string['socialmediadesc'] = 'Ingrese los enlaces de sus sitios sociales.';
$string['facebooksetting'] = 'Configuración de Facebook';
$string['facebooksettingdesc'] = 'Ingrese la dirección de su página de Facebook. https://www.facebook.com/pagename';
$string['twittersetting'] = 'Configuración de Twitter';
$string['twittersettingdesc'] = 'Ingrese la dirección de su página de Twitter. https://www.twitter.com/pagename';
$string['linkedinsetting'] = 'Configuración de LinkedIn';
$string['linkedinsettingdesc'] = 'Ingrese la dirección de su página de LinkedIn. https://www.linkedin.com/in/pagename';
$string['gplussetting'] = 'Configuración de Google Plus';
$string['gplussettingdesc'] = 'Ingrese la dirección de Google Plus. https://plus.google.com/pagename';
$string['youtubesetting'] = 'Configuración de YouTube';
$string['youtubesettingdesc'] = 'Ingrese la dirección de su sitio YouTube. https://www.youtube.com/channel/UCU1u6QtAAPJrV0v0_c2EISA';
$string['instagramsetting'] = 'Configuración de Instagram';
$string['instagramsettingdesc'] = 'Ingrese la dirección de su página de Instagram. https://www.instagram.com/name';
$string['pinterestsetting'] = 'Configuración de Pinterest';
$string['pinterestsettingdesc'] = 'Ingrese la dirección de su página de Pinterest. https://www.pinterest.com/name';
$string['quorasetting'] = 'Configuración de Quora';
$string['quorasettingdesc'] = 'Ingrese la dirección de su página de Quora. https://www.quora.com/name';
$string['footerbottomtext'] = 'Texto de la parte inferior izquierda del pie de página.';
$string['footerbottomlink'] = 'Enlace del Pie de Página';
$string['footerbottomlinkdesc'] = 'Ingresar la URL del enlace en la parte inferior del Pie de Página. http://www.yourcompany.com';
$string['footercolumn1heading'] = 'Contenido de la primera columna (izquierda)';
$string['footercolumn1headingdesc'] = 'Esta sección pertenece a la zona izquierda del pie de página en la página principal.';
$string['footercolumn1title'] = 'Título de la primera columna';
$string['footercolumn1titledesc'] = 'Agregar título a esta columna.';
$string['footercolumn1customhtml'] = 'Personalizar HTML';
$string['footercolumn1customhtmldesc'] = 'Usted puede personalizar el html de esta columna utilizando la caja de texto superior.';
$string['footercolumn2heading'] = 'Contenido de la segunda columna (Medio)';
$string['footercolumn2headingdesc'] = 'Esta sección pertenece a la zona central del pie de página en la página principal.';
$string['footercolumn2title'] = 'Título de la segunda columna';
$string['footercolumn2titledesc'] = 'Agregar título a esta columna.';
$string['footercolumn2customhtml'] = 'Personalizar HTML';
$string['footercolumn2customhtmldesc'] = 'Usted puede personalizar el html de esta columna utilizando la caja superior.';
$string['footercolumn3heading'] = 'Contenido de la tercera columna (Derecha)';
$string['footercolumn3headingdesc'] = 'Esta sección pertenece a la zona derecha del pie de página en la página principal.';
$string['footercolumn3title'] = 'Titulo de la Tercera Columna';
$string['footercolumn3titledesc'] = 'Agregar Titulo a esta columna.';
$string['footercolumn3customhtml'] = 'Personalizar HTML';
$string['footercolumn3customhtmldesc'] = 'Personalizar HTML de esta columna utilizando la caja superior.';
$string['footerbottomheading'] = 'Configuración de la parte inferior del pie de pagina';
$string['footerbottomdesc'] = 'Aquí puede especificar su propio enlace para mostrar hasta abajo del pie de página';
$string['footerbottomtextdesc'] = 'Agregar texto para configurar la parte inferior del Pie de Página.';
$string['poweredbyedwiser'] = 'Powered by Edwiser';
$string['poweredbyedwiserdesc'] = 'Desmarcar para eliminar \'Powered by Edwiser\' de su sitio.';

// Login Page Settings.
$string['loginsettings'] = 'Configuración de la página de login';
$string['navlogin_popup'] = 'Habilitar el popup de inicio de sesión';
$string['navlogin_popupdesc'] = 'Habilitar el popup de inicio de sesión en el encabezado.';
$string['loginsettingpic'] = 'Cargar imagen de fondo';
$string['loginsettingpicdesc'] = 'Cargar una imagen como fondo para el formulario de ingreso.';
$string['signuptextcolor'] = 'Color de texto para el Panel de Registro';
$string['signuptextcolordesc'] = 'Seleccione un color de texto para el Panel de Registro.';
$string['left'] = "Izquierdo";
$string['right'] = "Derecho";
$string['remember_me'] = "Recuérdame";
$string['brandlogopos'] = "Posición del logotipo marca";
$string['brandlogoposdesc'] = "Si está habilitado, el logotipo de la marca estará visible en la barra lateral derecha encima del formulario de inicio de sesión.";
$string['brandlogotext'] = "Sitio descripción";
$string['brandlogotextdesc'] = "Agregar texto para la descripción del sitio que se mostrará en la página de inicio de sesión y registro. Manténgalo en blanco si no desea poner ninguna descripción.";
$string['loginpagelayout'] = 'Diseño de página de inicio de sesión';
$string['loginpagelayoutdesc'] = '';
$string['centerlayout'] = 'Diseño Centrado';
$string['overlaylayout'] = 'Diseño de superposición derecha';

// License Settings.
$string['licensenotactive'] = '<strong>Alerta!</strong> Su licencia no está activa, por favor <strong>actívela</strong> en la configuración de RemUI.';
$string['licensenotactiveadmin'] = '<strong>Alerta!</strong> Licencia no activada , por favor <strong>active</strong> la licencia <a href="'.$CFG->wwwroot.'/admin/settings.php?section=themesettingremui#remuilicensestatus" > here</a>.';
$string['activatelicense'] = 'Activar Licencia';
$string['deactivatelicense'] = 'Desactivar Licencia';
$string['renewlicense'] = 'Renovar Licencia';
$string['active'] = 'Activa';
$string['notactive'] = 'No Activa';
$string['expired'] = 'Caducada';
$string['licensekey'] = 'Clave de Licencia';
$string['licensestatus'] = 'Estado de la Licencia';
$string['noresponsereceived'] = 'No se pudo conectar con el servidor. Por favor intentelo más tarde.';
$string['licensekeydeactivated'] = 'Codigo de Licencia desactivado.';
$string['siteinactive'] = 'Sitio inactivo (Presione activar licencia).';
$string['entervalidlicensekey'] = 'Por favor ingrese un código de licencia valido.';
$string['licensekeyisdisabled'] = 'Su código de licencia esta desactivado.';
$string['licensekeyhasexpired'] = "Su licencia ha caducado. Por favor renuévela.";
$string['licensekeyactivated'] = "Su licencia ha sido activada.";
$string['enterlicensekey'] = "Por favor escriba un código de licencia valido.";
$string['edwiserremuilicenseactivation'] = 'Edwiser RemUI Activación de Licencia';
$string['nolicenselimitleft'] = 'Límite máximo de activación alcanzado, No quedan activaciones.';

// News And Updates Page.
$string['newsandupdates'] = 'Novedades y actualizaciones';
$string['newupdatemessage'] = 'Nueva actualización disponible de RemUI.';
$string['currentversionmessage'] = 'La versión actual es ';
$string['downloadupdate'] = 'Descargar actualización';
$string['latestversionmessage'] = 'Está utilizando la última versión de RemUI.';
$string['rateremui'] = 'Valore RemUI';
$string['fullname']  = 'Nombre completo';
$string['providefeedback'] = 'Denos su opinión sobre RemUI';
$string['sendfeedback'] = 'Enviar opinión';
$string['recentnews'] = 'Noticias recientes';

// About Edwiser RemUI.
$string['aboutsettings'] = 'Sobre Edwiser RemUI';
$string['notenrolledanycourse'] = 'Usted no está inscrito(a) en ningún curso.';

/* My Course Page */
$string['resume'] = 'Reanudar';
$string['start'] = 'Comienzo';
$string['completed'] = 'Terminado';

// Course.
$string['graderreport'] = 'Informe de calificación';
$string['enroluser'] = 'Inscribir Usuarios';
$string['activityeport'] = 'Informe de actividades';
$string['editcourse'] = 'Editar curso';
$string['sections'] = "Secciones";

// Next Previous Activity.
$string['activityprev'] = 'Actividad previa';
$string['activitynext'] = 'Próxima actividad';

// Login Page.
$string['signin'] = 'Registrarse';
$string['signup'] = 'Darse de alta';
$string['noaccount'] = 'No tienes cuenta?';

// Incourse Page.
$string['backtocourse'] = 'Resumen del curso';

// Header Section.
$string['togglefullscreen'] = 'Pantalla completa';
$string['recent'] = 'Reciente';

// Course Stats.
$string['enrolledusers'] = 'Estudiantes <br>inscritos';
$string['studentcompleted'] = 'Estudiantes <br>completados';
$string['inprogress'] = 'En <br>progreso';
$string['yettostart'] = 'Aún <br>para empezar';

// Footer Content.
$string['followus'] = 'Síguenos';
$string['poweredby'] = 'Powered by Edwiser RemUI';

// Course Archive Page.
$string['mycourses'] = "Mis cursos";
$string['allcategories'] = 'Todas las categorías';
$string['categorysort'] = 'Ordenar categorías';
$string['sortdefault'] = 'Ordenar (ninguno)';
$string['sortascending'] = 'Ordenar de la A a la Z';
$string['sortdescending'] = 'Ordenar Z a A';

// Dashboard Blocks.
$string['viewcourse'] = "VER CURSO";
$string['searchcourses'] = "Buscar cursos";

$string['hiddencourse'] = 'Curso oculto';

// Usage tracking
$string['enableusagetracking'] = "Habilitar trakcing de uso";

$string['enableusagetrackingdesc'] = "<strong>AVISO DE SEGUIMIENTO DE USO</strong>

<hr class='text-muted' />

<p>Edwiser de ahora en adelante recopilará datos anónimos para generar estadísticas de uso del producto.</p>

<p>Esta información nos ayudará a guiar el desarrollo en la dirección correcta y la comunidad Edwiser prosperará.</p>

<p>Dicho esto, no recopilamos sus datos personales ni de sus alumnos durante este proceso. Puede desactivar esto desde el plugin siempre que desee optar por no participar en este servicio.</p>

<p>Una visión general de los datos recopilados está disponible <strong><a href='https://forums.edwiser.org/topic/67/anonymously-tracking-the-usage-of-edwiser-products' target='_blank'>aquí</a></strong>.</p>";