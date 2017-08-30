$( function( $ ) {

    disable_link();
    sidebar_popover();
    prevent_backspace();
    sidebar_toogle();
    close_pass_alert();
    active_sidebar();
    active_ribbon();
    full_screen();
    cirms_print();

    function cirms_print() {
        $("#print-all-graphs").on('click', function() {
            $("#all-graphs").print({
                globalStyles : false,
                mediaPrint : false,
                iframe : false,
                noPrintSelector : ".not-printable",
            });
        });

        $("#print-report-graph").on('click', function() {
            $("#report-graph").print({
                globalStyles : false,
                mediaPrint : false,
                iframe : false,
                noPrintSelector : ".not-printable",
            });
        });

        $("#print-classroom-graph").on('click', function() {
            $("#classroom-graph").print({
                globalStyles : false,
                mediaPrint : false,
                iframe : false,
                noPrintSelector : ".not-printable",
            });
        });

        $("#print-cluster-graph").on('click', function() {
            $("#cluster-graph").print({
                globalStyles : false,
                mediaPrint : false,
                iframe : false,
                noPrintSelector : ".not-printable",
            });
        });

        $("#print-complaint-graph").on('click', function() {
            $("#complaint-graph").print({
                globalStyles : false,
                mediaPrint : false,
                iframe : false,
                noPrintSelector : ".not-printable",
            });
        });
    }

    function full_screen() {

        $('.fullscreen-sot').click(function () {
            screenfull.toggle($('#service-order-table')[0]);
            $('.fullscreen-sot').find('span').toggleClass('glyphicon glyphicon-resize-full glyphicon glyphicon-resize-small');
        });

        $('.fullscreen-sr').click(function () {
            screenfull.toggle($('#software-table')[0]);
            $('.fullscreen-sr').find('span').toggleClass('glyphicon glyphicon-resize-full glyphicon glyphicon-resize-small');
        });

        $('.fullscreen-hr').click(function () {
            screenfull.toggle($('#hardware-table')[0]);
            $('.fullscreen-hr').find('span').toggleClass('glyphicon glyphicon-resize-full glyphicon glyphicon-resize-small');
        });

        $('.fullscreen-clsr').click(function () {
            screenfull.toggle($('#classroom-table')[0]);
            $('.fullscreen-clsr').find('span').toggleClass('glyphicon glyphicon-resize-full glyphicon glyphicon-resize-small');
        });

        $('.fullscreen-clrr').click(function () {
            screenfull.toggle($('#cluster-table')[0]);
            $('.fullscreen-clrr').find('span').toggleClass('glyphicon glyphicon-resize-full glyphicon glyphicon-resize-small');
        });

        $('.fullscreen-cl').click(function () {
            screenfull.toggle($('#classroom-graph')[0]);
            $('.fullscreen-cl').find('span').toggleClass('glyphicon glyphicon-resize-full glyphicon glyphicon-resize-small');
        });

        $('.fullscreen-clr').click(function () {
            screenfull.toggle($('#cluster-graph')[0]);
            $('.fullscreen-clr').find('span').toggleClass('glyphicon glyphicon-resize-full glyphicon glyphicon-resize-small');
        });

        $('.fullscreen-hsc').click(function () {
            screenfull.toggle($('#complaint-graph')[0]);
            $('.fullscreen-hsc').find('span').toggleClass('glyphicon glyphicon-resize-full glyphicon glyphicon-resize-small');
        });

        $('.fullscreen-rs').click(function () {
            screenfull.toggle($('#report-graph')[0]);
            $('.fullscreen-rs').find('span').toggleClass('glyphicon glyphicon-resize-full glyphicon glyphicon-resize-small');
        });
    }

    function disable_link() {
        $('a.link-disabled').click(function(){
            return false;
        });
    }

    function prevent_backspace(e) {
        var evt = e || window.event;
        if (evt) {
            var keyCode = evt.charCode || evt.keyCode;
            if (keyCode === 8) {
                if (evt.preventDefault) {
                    evt.preventDefault();
                } else {
                    evt.returnValue = false;
                }
            }
        }
    }

    function prevent_delete() {
        $('input[readonly]').on('keydown', function (e) {
            if (e.which === 8) {
                e.preventDefault();
            }
        });
    }

    function active_ribbon(e) {
        var url = window.location;
        // Will only work if string in href matches with location
        $('ul.navbar-ribbon-links a[href="'+ url +'"]').parent().addClass('active');

        // Will also work for relative and absolute hrefs
        $('ul.navbar-ribbon-links a').filter(function() {
            return this.href == url;
        }).parent().addClass('active');

        set_ribbon_parent_active();
    }

    function set_ribbon_parent_active() {
        if ( $('.ribbon-li-rooms ul li').hasClass('active') ) {
            $('.ribbon-li-rooms').addClass('active');
        }
        else if ( $('.ribbon-li-offices ul li').hasClass('active') ) {
            $('.ribbon-li-offices').addClass('active');
        }
        else if ( $('.ribbon-li-reports ul li').hasClass('active') ) {
            $('.ribbon-li-reports').addClass('active');
        }
        else if ( $('.ribbon-li-caret ul li').hasClass('active') ) {
            $('.ribbon-li-caret').addClass('active');
        }
    }


    function active_sidebar() {

        var url = window.location;
        // Will only work if string in href matches with location
        $('ul.sidebar-nav a[href="'+ url +'"]').parent().addClass('active');

        // Will also work for relative and absolute hrefs
        $('ul.sidebar-nav a').filter(function() {
            return this.href == url;
        }).parent().addClass('active');

        // Checking if popover li is active
        set_sidebar_parent_active();
    }

    // Checking if popover li is active
    function set_sidebar_parent_active() {

        if ( $('#service-order-pop ul li').hasClass('active') ) {
            $('#service-orders').addClass('active');
        }
        else if ( $('#user-pop ul li').hasClass('active') ) {
            $('#users').addClass('active');
        }
        else if ( $('#department-pop ul li').hasClass('active') ) {
            $('#departments').addClass('active');
        }
        else if ( $('#computer-pop ul li').hasClass('active') ) {
            $('#computers').addClass('active');
        }
        else if ( $('#classroom-pop ul li').hasClass('active') ) {
            $('#classrooms').addClass('active');
        }
        else if ( $('#cluster-pop ul li').hasClass('active') ) {
            $('#clusters').addClass('active');
        }
        else if ( $('#report-pop ul li').hasClass('active') ) {
            $('#reports').addClass('active');
        }
    }

    function sidebar_toogle() {
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#sub-wrapper").toggleClass("active");
            $(this).find('span').toggleClass('fa fa-angle-double-left fa fa-angle-double-right');

            $.ajax({
                url: 'ajax_user/update_user_sidebar_status'
            }); 
        });
    }

    function close_pass_alert() {
        $(".close").click(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'ajax_user/update_pass_alert_status'
            }); 
        });
    }

    function sidebar_popover() {
        $('[rel="popover"]').popover ({
            container: 'body',
            html: true,
            trigger: "manual",
            animation: false,
            content: function () {
                var clone = $($(this).data('popover-content')).clone(true).removeClass('hidden');
                return clone;
            }
        })
        .click(function(e){
            e.preventDefault();

            $('[rel=popover]').each(function(){
                $(this).popover('hide');
            });

            $(this).popover('show');
        });

        $('body').on('click', function (e) {
            $('[rel="popover"]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    }
});

