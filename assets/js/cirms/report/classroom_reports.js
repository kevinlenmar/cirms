$( function( $ ) {
    var obj = {},
        base_url = $('#base_url').val(),
        date = new Date(),
        date_from = new Date(date.getFullYear(), date.getMonth(), 1),
        date_to = new Date(date.getFullYear(), date.getMonth() + 1, 0);

    obj.loader = '<img src="assets/images/loader/loader.gif" class="loader" />';

    init();

    // Initialize date
    date_picker(date_from, '#date_from', 'bottom right');
    date_picker(date_to, '#date_to', 'bottom right');

    obj.date_from = $('#date_from').val();
    obj.date_to = $('#date_to').val();
    set_report_title('Classroom ', obj.date_from, obj.date_to);

    $('[data-role="date_filter"]').click(function(e){
        obj.date_from = $('#date_from').val();
        obj.date_to = $('#date_to').val();
        obj.classroom_report.ajax.reload();
        set_report_title('Classroom ', obj.date_from, obj.date_to);
    });

    $('[data-role="type"]').click(function(e){
        $(this).addClass('active').siblings().removeClass('active');
        obj.classroom_report.ajax.reload();
    });

    $( document ).on( 'click', '[data-bind]', function(e){
        e.preventDefault();
        var url = $( this ).data( 'bind' ),
            room_no = $( this ).data( 'id' ),
            method = $( this ).data( 'method' );

            load_modal( url, room_no, method );
    } );

    obj.classroom_report = $('#classroom-report').DataTable({
        'processing': true,
        'serverSide': true,
        'responsive': true,
        'paging': false,
        'bSort': false,
        'dom': "<'row'<'col-sm-4'l><'col-sm-5 text-center visible-lg'B><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        'buttons': [
            {
                'extend': 'excel',
                'text': '<i class="fa fa-file-excel-o fg-green"></i>&nbsp;Excel',
                'exportOptions': {
                    'columns': ':visible'
                }
            },
            {
                'extend': 'pdfHtml5',
                'text': '<i class="fa fa-file-pdf-o fg-red"></i>&nbsp;PDF',
                'orientation': 'portrait',
                'pageSize': 'LEGAL',
                'exportOptions': {
                    'columns': ':visible'
                }
            },
            {
                'extend': 'print',
                'text': '<i class="fa fa-print fg-cyan"></i>&nbsp;Print',
                'exportOptions': {
                    'columns': ':visible'
                },
                customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<div class="print-div"><img src="'+ base_url + 'assets/images/logo/citu-o.png" style="position: absolute;" /></div>'
                        );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }
            },
            {
                'extend': 'colvis',
                'text': '<i class="fa fa-filter"></i>&nbsp;Filter'
            }
        ],
        'ajax': {
            'url' : 'ajax_classroom/get_classroom_report_for_table',
            'data': function ( d ) {
                d.type = $('.active[data-role="type"]').text().toLowerCase();
                d.date_from = obj.date_from;
                d.date_to = obj.date_to;
            }
        },
        'deferRender': true,
        'columns': [
            { 'data': 'room_no'},
            { 'data': 'type', 'sClass': 'caps text-center' },
            { 'data': 'no_of_reports_software', 'sClass': 'text-center' },
            { 'data': 'no_of_reports_hardware', 'sClass': 'text-center' },
            { 'data': 'total_reports_per_cluster', 'sClass': 'text-center text-bold' },
            { 'data': 'actions', 'sClass': 'text-center text-bold' }
        ],
        'columnDefs': [
            {
                'data': 'actions',
                'targets': -1,
                'sortable': false,
                'render' : function ( data, type, row ) {
                    var html = ' \
                        <div class="text-center"> \
                            <div class="btn-group"> \
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-cirms" data-toggle="dropdown"> \
                                    <i class="fa fa-chevron-down fa-fw"></i> \
                                </button> \
                                <ul class="dropdown-menu pull-right" role="menu"  style="padding: 0 0 7px 0"> \
                                    <li> \
                                        <span class="dropdown-title text-center">Action Bar</span> \
                                    </li> \
                                    <li> \
                                        <a href="javascript:void(0)" data-method="view" data-bind="view_report_details" data-id="' + row.room_no + '"><i class="fa fa fa-external-link fa-fw fg-cyan"></i> View Details</a> \
                                    </li> \
                                </ul> \
                            </div> \
                        </div> \
                    ';
                    if(row.room_no !== 'OVERALL TOTAL'){
                        return html;
                    }else{
                        return null;
                    }
                }
            }
        ],
        'language': {
            "processing": '<div class="processing-wrapper"> \
                                <div><i class="fa fa-spinner fa-spin"></i> Fetching... Please wait...</div> \
                            </div>',
            'emptyTable': 'No cluster report(s) available in the database',
            'zeroRecords': 'No cluster report(s) found!',
            "infoFiltered": "",
        },
    });

    function ajax_get_classroom_report_details( $modal ) {
        var $form = $modal.find( 'form' ),
            $field = $modal.find( 'div' );

        $('[data-role="complaint_type"]').click(function(e){
            $(this).addClass('active').siblings().removeClass('active');

            obj.report_details.ajax.reload();
        });

        obj.report_details = $('#report-details').DataTable({
            'processing': true,
            'serverSide': true,
            'responsive': true,
            'ajax': {
                'url' : 'ajax_service_order/get_report_details_for_table/' + obj.room_no,
                'data': function ( d ) {
                    d.type = $('.active[data-role="complaint_type"]').text().toLowerCase();
                    d.date_from = obj.date_from;
                    d.date_to = obj.date_to;
                }
            },
            'deferRender': true,
            'order': [[0, 'desc']],
            'columns': [
                { 'data': 'ref_no', 'sClass': 'text-center' },
                { 'data': 'computer_name' },
                { 'data': 'complaint' },
                {
                    'data': 'complaint_details',
                    'width': '30%'
                },
                { 'data': 'datetime_reported' },
            ],
            'language': {
                "processing": '<div class="processing-wrapper"> \
                                    <div><i class="fa fa-spinner fa-spin"></i> Fetching ... Please wait...</div> \
                                </div>',
                'emptyTable': 'No computer history available in the database!',
                'zeroRecords': 'No computer history found!',
                "infoFiltered": ""
            }
        });
    };

    obj.ajax_get_modal_content = function( ajax_url, $modal ) {
        return $.ajax({
            url : ajax_url,
            type : 'get',
            dataType : 'html',
            beforeSend: function() {
                $modal.find( '.modal-content' ).html( obj.loader );
            },
            success: function( response ) {
                var html = $( $.parseHTML( response ) ),
                    content = html.filter( '.modal-content' ).html();

                $modal.find( '.modal-content' ).html( content );
            },
            error: function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    }

    function load_modal( url, room_no, method ) {
        var $modal = $( '#large' ),
            ajax_url = 'modal/' + url;

            obj.method = method;
            obj.room_no = room_no;

        switch( obj.method ){
            case 'view':
                obj.ajax_get_modal_content( ajax_url, $modal ).done( function(){
                    ajax_get_classroom_report_details( $modal );
                    $modal.modal('show');
                } );
                break;
            default :
                obj.ajax_get_modal_content( ajax_url, $modal ).done( function(){
                    $modal.modal( {
                        show : true,
                        backdrop: 'static',
                    } );
                } );
                break;
        }
    }

});
