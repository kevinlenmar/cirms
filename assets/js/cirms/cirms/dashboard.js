$( function( $ ) {
    var obj = {};

    obj.loader = '<li><div class="loader-badge"><img src="assets/images/loader/citu-loader.gif" class="loader" style="width: 50px; height: 50px;" /></div></li>';

    report_graph()
    hardware_software_graph();

    obj.length = 10;
    obj.total = $('.total').text();

    timeline();
    
    classroom_bar_graph( $('#classroom_report_year').val() );
    cluster_bar_graph( $('#cluster_report_year').val() );

    $( document ).on( 'change', '#classroom_report_year', function(){
        var value = $(this).val();
        $( '#classroom-bar-chart' ).empty();
        classroom_bar_graph( value );
    });

    $( document ).on( 'change', '#cluster_report_year', function(){
        var value = $(this).val();
        $( '#cluster-bar-chart' ).empty();
        cluster_bar_graph( value );
    });

    $('#load_more').click( function( e ) {
        e.preventDefault();
        if( obj.length <= obj.total ) {
            $('.timeline_body').append(obj.loader);
            obj.length += 5;
            timeline();
        }
        else {
            $('#load_more').remove();
            $('.error').html('<div class="text-center cool-text"><h3>No more entries</h3></div>');
        }
    });

    function wordwrap( str, width, brk, cut ) {
        brk = brk || '\n';
        width = width || 75;
        cut = cut || false;

        if (!str) { return str; }

        var regex = '.{1,' +width+ '}(\\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\\S+?(\\s|$)');

        return str.match( RegExp(regex, 'g') ).join( brk );
    }

    function timeline(){
        var $timeline = $( '.timeline_body' ),
            $no_display = $( '.error' );
        return $.ajax({
            url : 'ajax_service_order/get_service_order_timeline',
            type : 'get',
            dataType : 'json',
            data : { length : obj.length },
            success : function( result ) {
                $timeline.empty();

                if( result instanceof Array ) {
                    var html = "";
                    $.each( result, function( index, value ) {
                        if( result[index].if_inverted == 1 ) {
                            html += '<li class="timeline-inverted">';
                        }
                        else {
                            html += '<li>';
                        }
                            html += '<div class="timeline-badge bg-' + result[index].color + ' "><small>' + result[index].cluster_code + '</small></div> \
                            <div class="timeline-panel"> \
                                <div class="timeline-heading"> \
                                    <h4 class="timeline-title">' + result[index].computer_name + '</h4> \
                                    <p><small class="text-muted"><i class="fa fa-sitemap"></i> ' + wordwrap(result[index].cluster_name, 40,'<br>') + '</small> \
                                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> ' + $.format.date(result[index].date_reported + '00:00:00', "MMMM dd, yyyy") + ' ' + result[index].time_reported + '</small></p> \
                                    <p><b>Complaint: </b>' + result[index].complaint + '</p> \
                                </div> \
                                <div class="timeline-body"> \
                                    <p class="complaint-timeline"><b>- </b>' + result[index].complaint_details + '</p> \
                                    <div class="pull-right date_added"> \
                                        <small class="text-muted">' + result[index].date_added + '</small> \
                                    </div> \
                                </div> \
                            </div> \
                        </li>';
                    });

                    $timeline.html(html);
                    $('.date_added').prettydate({
                                autoUpdate: true,
                                duration: 1000
                            });
                }
                else {
                    $timeline.html('');
                    $no_display.html('<div class="text-center cool-text"><h1>No recently encoded</h1></div>');
                }
            },
            error : function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    }

    function report_graph() {
        $.ajax({
            type: "GET",
            url: "ajax_service_order/get_report_graph",
            cache: false,
            dataType: "json",
            timeout: 30000,
            success : function (data) {
                if(data) {
                    Morris.Area({
                        element: 'report-area-chart',
                        data: data,
                        xkey: 'month_year_reported',
                        ykeys: ['no_of_reports'],
                        labels: ['No. of Reports'],
                        hideHover: 'auto',
                        resize: true,
                        parseTime: false,
                        xLabelAngle: 10,
                        lineColors: ['#80858C'],
                        fillOpacity: 0.5,
                        pointSize: 0,
                        axes: true
                    });
                }
                else {
                    $('#report-area-chart').html('<div class="text-center cool-text" style="position: absolute"><h3>No entries</h3></div>');
                }
            },
            error : function (xmlHttpRequest, textStatus, errorThrown) {
                alert("Error " + errorThrown);
                if(textStatus==='timeout')
                    alert("Complaint Reports graph request timed out");
            }
        });
    }

    function hardware_software_graph() {
        $.ajax({
            type: "GET",
            url: "ajax_service_order/get_report_hardware_software",
            cache: false,
            dataType: "json",
            timeout: 30000,
            success : function (data) {
                if(data) {
                    Morris.Bar({
                        element: 'hardware-software-line-chart',
                        data: data,
                        xkey: 'month_year_reported',
                        ykeys: ['no_of_reports_hardware', 'no_of_reports_software'],
                        labels: ['Hardware', 'Software'],
                        hideHover: 'auto',
                        xLabelAngle: 10,
                        barColors: ['#780f0d', '#e5b524'],
                        resize: true,
                        parseTime: false,
                        axes: true
                    });
                }
                else {
                    $('#hardware-software-line-chart').html('<div class="text-center cool-text" style="position: absolute"><h3>No entries</h3></div>');
                }
            },
            error : function (xmlHttpRequest, textStatus, errorThrown) {
                alert("Error " + errorThrown);
                if(textStatus==='timeout')
                    alert("Complaint Reports graph request timed out");
            }
        });
    }



    function classroom_bar_graph(year) {
        $.ajax({
            type: "GET",
            url: "ajax_service_order/get_report_counts_classroom/" + year,
            cache: false,
            dataType: "json",
            timeout: 30000,
            success : function (data) {
                if(data) {
                    Morris.Bar({
                        element: 'classroom-bar-chart',
                        data: data,
                        xkey: 'designation',
                        ykeys: ['no_of_reports'],
                        labels: ['No. of Reports'],
                        hideHover: 'auto',
                        resize: true,
                        xLabelAngle: 10,
                        hideHover: 'auto',
                        axes: true
                    });
                }
                else {
                    $('#classroom-bar-chart').html('<div class="text-center cool-text" style="position: absolute"><h3>No entries</h3></div>');
                }
            },
            error : function (xmlHttpRequest, textStatus, errorThrown) {
                alert("Error " + errorThrown);
                if(textStatus==='timeout')
                    alert("Classroom Graph request timed out");
            }
        });
    }

    function cluster_bar_graph(year) {
        $.ajax({
            type: "GET",
            url: "ajax_service_order/get_report_counts_cluster/" + year,
            cache: false,
            dataType: "json",
            timeout: 30000,
            success : function (data) {
                if(data) {
                    Morris.Bar({
                        element: 'cluster-bar-chart',
                        data: data,
                        xkey: 'designation',
                        ykeys: ['no_of_reports'],
                        labels: ['No. of Reports'],
                        hideHover: 'auto',
                        resize: true,
                        xLabelAngle: 10,
                        hideHover: 'auto',
                        barColors: ['#4da74d'],
                        axes: true
                    });
                }
                else {
                    $('#cluster-bar-chart').html('<div class="text-center cool-text" style="position: absolute"><h3>No entries</h3></div>');
                }
            },
            error : function (xmlHttpRequest, textStatus, errorThrown) {
                alert("Error " + errorThrown);
                if(textStatus==='timeout')
                    alert("Cluster Graph request timed out");
            }
        });
    }
});
