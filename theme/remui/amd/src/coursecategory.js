define(['jquery', 'theme_remui/bootstrap-select', 'core/templates', 'theme_remui/jquery-toolbar', 'core/ajax'], function(jQuery, bsselect, templates, toolbar, Ajax) {

    // Globals
    var filterobj;

    // Filter selectors
    var sortfilter = jQuery('select#sortfilter.selectpicker');
    var viewfilter = jQuery('.togglebtn');
    var searchfilter   = jQuery('#coursesearch2');
    var categoryfilter = jQuery('select#categoryfilter.selectpicker');

    // Tab selector
    var tabsselector = jQuery(".nav-tabs .nav-item");
    var coursestab   = jQuery('#coursestab');
    var mycoursestab = jQuery('#mycoursestab');

    // Courses Region Selector
    var coursesregion   = jQuery('#coursesregion div.content');
    var mycoursesregion = jQuery('#mycoursesregion div.content');

    // Pagination Selector
    var coursespagination   = jQuery('#coursespagination');
    var mycoursespagination = jQuery('#mycoursespagination');

    // View toggler
    var togglebtn = jQuery(".togglebtn");


    // view templates
    var gridtemplate = 'theme_remui/course_card_grid';
    var listtemplate = 'theme_remui/course_card_list';

    var pageheaderactions = '.page-header-actionss';


    // Initialization of courses
    jQuery(document).ready(function (){

        filterobj = new CategoryFilters(); // Global object for filters
        // filterobj.initAttributes(); // Initialize global filter object with default values

        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }

        if (vars['categoryid'] && vars['categoryid'] != 0) {
            filterobj.category = vars['categoryid'];
        }

        if (vars['categorysort'] != undefined) {
            filterobj.sort = vars['categorysort'];
        }

        if (vars['search'] != undefined) {
            filterobj.search = vars['search'];
        }

        if(vars['mycourses'] && vars['mycourses'] != 0)
        {
            filterobj.tab = true;
            if ($("body").hasClass("notloggedin")) {
                filterobj.tab = false;
            }
        }
        generateFilters(filterobj); // This will create filters
        generateCourseCards(); // Course cards Generation
    });


    // Filters Generation
    function generateFilters(filterdata)
    {
        jQuery( ".selectpicker" ).each(function() {
            jQuery(this).selectpicker();
        });

        if (filterdata.category != "0") {
           jQuery("#categoryfilter.selectpicker").selectpicker('val', filterdata.category);
        }

        if (filterdata.sort != null) {
           jQuery("#sortfilter.selectpicker").selectpicker('val', filterdata.sort);
        }

        if (filterdata.sort != "") {
           jQuery("#coursesearchbox").val(filterdata.search);
        }

        // Put animation over here
        jQuery(".category-filters").removeClass('d-none');
    }

    // Course cards initialization function
    function generateCourseCards()
    {
        // Check if Filters are modified and need to fetch the courses
        if(!filterobj.isFilterModified){
            return;
        }
        // Fetch the courses
        getCourses();
    }

    function destroyCourseCards()
    {
        // Find active tab to append the course cards
        var destroytab = (filterobj.tab) ? mycoursesregion : coursesregion;
        // Empty the courses region
        jQuery(destroytab).empty();

        // Destroy the pagination also
        if(filterobj.pagination){
            var destroypagination = (filterobj.tab) ? mycoursespagination : coursespagination;
            jQuery(destroypagination).empty();
        }

    }

    // Main category filters class
    function CategoryFilters() {

        var _pageobj = {courses: 0, mycourses: 0};
        var _obj = {
            category:"all", // Category id
            sort: null, // Sorting
            search:"", // Searching string
            tab : false, // if true, means mycourses tab is active
            page: _pageobj, // This object consist of page number that is currently active, has mycourses and all courses tab page number
            pagination :true, // if True, regenerate the pagination on any action performed
            view : null, // initially it is null to detect initial change in view, String grid - view in grid format, String list - list format
            isFilterModified: true  // This filterModified true will tell that we need to fetch the courses otherwise show old fetched data, 
        };

        _obj.initAttributes = function(){
            _obj.category = 'all';
            _obj.sort = null;
            _obj.search = '';
            _obj.tab = false;
            _obj.page = _pageobj;
            _obj.pagination = true;
            _obj.view = null;
            _obj.isFilterModified = true;
        }

        _obj.initPagination = function(){
            _obj.page = {courses: 0, mycourses: 0};
        }
        return _obj;
    }

    // Ajax to fetch the course
    // and also append those courses to the page
    // if pagination is enabled it will also generate new pagination
    function getCourses() {
        $('.courses-tabs .courses-loader-wrap').show();
        // Find active tab to append the course cards
        var appendtab = (filterobj.tab) ? mycoursesregion : coursesregion;
        var appendpagination = (filterobj.tab) ? mycoursespagination : coursespagination;
        var  service_name = 'theme_remui_get_courses';
        var getcourses = Ajax.call([
            {
                methodname: service_name,
                args: {
                    data: JSON.stringify(filterobj)
                }
            }
        ]);
        getcourses[0].done(function(response) {
            response = JSON.parse(response);
            jQuery(pageheaderactions).empty();
            if (response['hasmanagebutton'] == true) {
                jQuery(pageheaderactions).append(response['managebuttons']);
            }

            // Get the view
            var viewobj = (filterobj.view == null)?response['view'] : filterobj.view;

            // Select the template to render according to view
            var rendertemplate = (viewobj == 'grid' || response['latest_card'])? gridtemplate : listtemplate;

            // always render grid teplate on mobile screen and when latest cards setting is on
            if (window.screen.width <= 480 || response['latest_card']) {
                rendertemplate = gridtemplate ;
                viewobj = 'grid'
            }

            // update the view
            updateView(viewobj);

            updateCards(response['latest_card']);

            var courses = response['courses'];
    
            if (courses.length > 0) {
                for (var i = 0; i < courses.length; i++) {
                    // This will call the function to load and render our template.
                    templates.render(rendertemplate, courses[i])
                    // It returns a promise that needs to be resoved.
                    .then(function(html, js) {
                        // Here eventually I have my compiled template, and any javascript that it generated.
                        // The templates object has append, prepend and replace functions.
                        templates.appendNodeContents(appendtab, html, js);

                        // Show options button on course card
                        // check if not mycourse tab
                        // This is very bad code, couldn't do it another way,
                        // it get called each time a single card is added to dom, try to improve it
                        if (!filterobj.tab && !response['latest_card']) {
                            jQuery('.showoptions').each(function(){
                                jQuery(this).toolbar({
                                    content: jQuery(this).data('toolbar'),
                                    style: 'primary'
                                });
                            });
                        }
                    }).fail(function(ex) {
                        // Deal with this exception (I recommend core/notify exception function for this).
                    });
                }

            } else {
                var htmldata = '<div class="alert alert-warning alert-dismissible  w-full mx-10" role="alert">';
                htmldata += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button> No Courses Found</div>';
                templates.appendNodeContents(appendtab, htmldata, '');

            }

            // Pagination html
            // Check if pagination is enabled
            if(filterobj.pagination) {
                templates.appendNodeContents(appendpagination, response['pagination'], '');
            }
            jQuery('.courses-tabs .courses-loader-wrap').hide();
        }).fail(function (err) {
            jQuery('.courses-tabs .courses-loader-wrap').show();
        });
    }


    // ***************** Actions ***************************

    // This is for, Toolbar redirection not working
    jQuery(document).delegate('.tool-item', 'click', function () {
        window.location = jQuery(this).attr('href');
    });

    // Category filter 
    jQuery(categoryfilter).on('changed.bs.select', function (e) {
        filterobj.category   = e.target.value;
        filterobj.initPagination();
        updatePage();
    });
    // Sorting Filter
    jQuery(sortfilter).on('changed.bs.select', function (e) {
        filterobj.sort = e.target.value;
        updatePage();
    });
    // Course Tab Selector
    jQuery(coursestab).on('click', function(){
        filterobj.tab = false;
        updatePage();
    });
    // My Course Tab Selector
    jQuery(mycoursestab).on('click', function(){
        filterobj.tab = true;
        updatePage();
    });

    // Search Filter
    jQuery(searchfilter).on('submit', function(e){
        e.preventDefault();
        filterobj.initPagination();
        filterobj.search = jQuery('#coursesearchbox').val();
        updatePage();
    });

    // View toggler
    jQuery(togglebtn).on('click', function(){
        var clckviewbtn = jQuery(this).attr('data-view');
        filterobj.view = clckviewbtn;
        M.util.set_user_preference('course_view_state', clckviewbtn, null);
        updatePage();
    });

    // This function is commented because no one is going to resize the screen

    // jQuery(window).resize(function(){
    //     // filterobj.initPagination();
    //     updatePage();
    // });

    function updateView(view){
        if(view == 'grid'){
            filterobj.view = 'grid';
            jQuery('.tab-content').addClass('grid-view').removeClass('list-view');
            jQuery('.grid_btn').addClass('btn-primary active');
            jQuery('.list_btn').removeClass('btn-primary active');
        } else {
            filterobj.view = 'list';
            jQuery('.tab-content').addClass('list-view').removeClass('grid-view');
            jQuery('.grid_btn').removeClass('btn-primary active');
            jQuery('.list_btn').addClass('btn-primary active');
        }
    }

    function updateCards(latest){
        if(latest){
            jQuery('.tab-content').addClass('latest-cards');
            jQuery('.viewtoggler').addClass('hidden');
        } else {
            jQuery('.tab-content').removeClass('latest-cards');
            jQuery('.viewtoggler').removeClass('hidden');
        }
    }

    // Pagination Click Event
    jQuery(document).delegate('.tab-pane .pagination .page-item .page-link', 'click',function(e){
        e.preventDefault();
        // Update the page number in object for mycourses as well as all courses tab
        var linkdata = e.target.href;
        if( linkdata === undefined ) {
            linkdata = e.target.parentElement.href;
            if(linkdata === undefined){
                linkdata = e.target.parentElement.parentElement.href;
            }
        }

        var hashes = linkdata.slice(linkdata.indexOf('?') + 1).split('&');
        var vars = [], hash;
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }

        if(filterobj.tab){
            filterobj.page.mycourses = vars['page'];
        }else{
            filterobj.page.courses = vars['page'];
        }

        updatePage();
    });

    function updatePage()
    {
        // Destroy the cards from page
        destroyCourseCards();
        // Create courses cards again
        generateCourseCards();
    }

});

