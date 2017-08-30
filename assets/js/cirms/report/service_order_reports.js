$( function( $ ) {
    var obj = {},
        base_url = $('#base_url').val(),
        date = new Date(),
        date_from = new Date(date.getFullYear(), date.getMonth(), 1),
        date_to = new Date(date.getFullYear(), date.getMonth() + 1, 0);

    init();

    // Initialize date
    date_picker(date_from, '#date_from', 'bottom right');
    date_picker(date_to, '#date_to', 'bottom right');

    obj.date_from = $('#date_from').val();
    obj.date_to = $('#date_to').val();
    set_report_title('Service Order ', obj.date_from, obj.date_to);

    $('[data-role="date_filter"]').click(function(e){
        obj.date_from = $('#date_from').val();
        obj.date_to = $('#date_to').val();
        obj.service_order_report.ajax.reload();
        set_report_title('Service Order ', obj.date_from, obj.date_to);
    });

    $('[data-role="type"]').click(function(e){
        $(this).addClass('active').siblings().removeClass('active');
        obj.service_order_report.ajax.reload();
    });

    obj.service_order_report = $('#service-order-report').DataTable({
        'processing': true,
        'serverSide': true,
        'responsive': true,
        'lengthMenu': [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        'dom': "<'row'<'col-sm-4'l><'col-sm-5 text-center visible-lg'B><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        'buttons': [
            {
                'extend': 'excel',
                'text': '<i class="fa fa-file-excel-o fg-green"></i>&nbsp;Excel',
                // 'exportOptions': {
                //     'columns': ':visible'
                // }
            },
            {
                'extend': 'pdfHtml5',
                'text': '<i class="fa fa-file-pdf-o fg-red"></i>&nbsp;PDF',
                'orientation': 'landscape',
                'pageSize': 'LEGAL',
                // 'exportOptions': {
                //     'columns': ':visible'
                // }
            },
            {
                'extend': 'print',
                'text': '<i class="fa fa-print fg-cyan"></i>&nbsp;Print',
                // 'exportOptions': {
                //     'columns': ':visible'
                // },
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
            'url' : 'ajax_service_order/get_service_order_done_for_table',
            'data': function ( d ) {
                d.type = $('.active[data-role="type"]').text().toLowerCase();
                d.date_from = obj.date_from;
                d.date_to = obj.date_to;
            }
        },
        'deferRender': true,
        'order': [[0, 'asc']],
        'columns': [
            { 'data': 'ref_no' },
            { 'data': 'emp_name' },
            { 'data': 'cluster_code' },
            { 'data': 'computer_name' },
            { 'data': 'brand_clone_name' },
            { 'data': 'complaint_type', 'sClass': 'caps' },
            { 'data': 'complaint_and_details' },
            { 'data': 'datetime_reported' },
            { 'data': 'received_by' },
            { 'data': 'assigned_to' },
            { 'data': 'datetime_finished' },
            { 'data': 'action_taken' },
            { 'data': 'returned_to'  },
            { 'data': 'property_clerk' },
            { 'data': 'property_date_received' },
        ],
        'language': {
            "processing": '<div class="processing-wrapper"> \
                                <div><i class="fa fa-spinner fa-spin"></i> Fetching... Please wait...</div> \
                            </div>',
            'emptyTable': 'No service order report available in the database',
            'zeroRecords': 'No service order report found!',
            "infoFiltered": ""
        }
    });

});
