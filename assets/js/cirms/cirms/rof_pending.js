$( function( $ ) {
    var obj = {};

    init();

    obj.loader = '<img src="assets/images/loader/loader.gif" class="loader" />';
    obj.status = $('.active[data-role="status"]').text().toLowerCase();
    obj.sess_user_type = $('#sess_user_type').text();
    obj.user_name = $('#sess_user_name').text();

    $('[data-role="status"]').click(function(e){
        $(this).addClass('active').siblings().removeClass('active');
        obj.status = $('.active[data-role="status"]').text().toLowerCase();
        obj.user_rof_pending_list.ajax.reload();
    });

    $( document ).on( 'click', '[data-bind]', function(e){
        e.preventDefault();

        var url = $( this ).data( 'bind' ),
            ref_no = $( this ).data( 'id' ),
            cluster_id = $( this ).data( 'cluster-id' ),
            complaint_type = $( this ).data( 'complaint-type' ),
            method = $( this ).data( 'method' );

        if(method == 'print') {
            obj.print_service_order_form(ref_no);
        }
        else {
            load_modal( url, ref_no, cluster_id, complaint_type, method );
        }
    } );

    $( document ).on( 'click', '#small [type="submit"]', function(e){
        e.preventDefault();
        var $small_modal = $('#small');

        switch(obj.method){
            case 'replaced' :
                obj.ajax_mark_replaced_service_order_by_id( $small_modal );
                break;
            case 'ordering' :
                obj.ajax_mark_for_ordering_service_order_by_id( $small_modal );
                break;
        }
    } );

	obj.user_rof_pending_list = $('#rof_pending').DataTable({
        'processing': true,
        'serverSide': true,
        'responsive': true,
        'ajax': {
            'url' : 'ajax_user/get_rof_pending_details_for_table',
            'data': function ( d ) {
                d.status = obj.status;
            }
        },
        'deferRender': true,
        'order': [[0, 'asc']],
        'columns': [
            { 'data': 'ref_no', 'sClass': 'text-center' },
            { 'data': 'computer_name' },
            { 'data': 'complaint_type', 'sClass': 'caps' },
            { 'data': 'complaint' },
            {
                'data': 'complaint_details',
                'width': '30%'
            },
            { 'data': 'datetime_reported' },
            { 'data': 'actions' },
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
                                        <a href="javascript:void(0)" data-method="view" data-bind="view_service_order" data-id="' + row.ref_no + '"><i class="fa fa fa-external-link fa-fw fg-cyan"></i> View Details</a> \
                                    </li> ';
                                    if( obj.status === 'pending') {
                                        if( obj.sess_user_type === 'administrator' ){
                                            html += '<li> \
                                                <a href="javascript:void(0)" data-method="replaced" data-bind="confirmation" data-id="' + row.ref_no + '"><i class="fa fa-check-square-o fa-fw fg-orange"></i> Replaced</a> \
                                            </li> \
                                            <li> \
                                                <a href="javascript:void(0)" data-method="ordering" data-bind="confirmation" data-id="' + row.ref_no + '"><i class="fa fa-plus-square fa-fw fg-green"></i> For Ordering</a> \
                                            </li>';
                                        }
                                    }
                                    if( obj.status === 'ordering') {
                                        if( obj.sess_user_type === 'administrator' ){
                                            html += '<li> \
                                                <a href="javascript:void(0)" data-method="replaced" data-bind="confirmation" data-id="' + row.ref_no + '"><i class="fa fa-check-square-o fa-fw fg-orange"></i> Replaced</a> \
                                            </li> \
                                            <li class="visible-lg"> \
                                                <a href="javascript:void(0)" data-method="print" data-bind="print_service_order_form" data-id="' + row.ref_no + '" id="print_service_order_form"><i class="fa fa-print fa-fw"></i> Print Form</a> \
                                            </li>';
                                        }
                                    }
                                    else if(obj.status === 'replaced'){
                                        html += '<li class="visible-lg"> \
                                            <a href="javascript:void(0)" data-method="print" data-bind="print_service_order_form" data-id="' + row.ref_no + '" id="print_service_order_form"><i class="fa fa-print fa-fw"></i> Print Form</a> \
                                        </li>';
                                    }
                                html += '</ul> \
                            </div> \
                        </div> \
                    ';
                    return html;
                }
            }
        ],
        'language': {
            "processing": '<div class="processing-wrapper"> \
                                <div><i class="fa fa-spinner fa-spin"></i> Fetching ... Please wait...</div> \
                            </div>',
            'emptyTable': 'No task(s) available in the database!',
            'zeroRecords': 'No task(s) available!',
            "infoFiltered": ""
        }
    });

    obj.print_service_order_form = function(ref_no) {
        ajax_get_service_order_details_by_ref_no( ref_no ).done( function() {
            window.print();
        });
    };

    obj.ajax_mark_replaced_service_order_by_id = function( $small_modal ){
        var $submit = $('[type="submit"]');

        $.ajax({
            url : 'ajax_service_order/mark_replaced_service_order_by_id',
            type : 'post',
            dataType : 'json',
            data : { ref_no : obj.ref_no, user_name : obj.user_name },
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ){
                if( result.status )
                {
                    obj.user_rof_pending_list.ajax.reload();
                    toastr.success( 'Marked as Replaced!', "CIRMS | Service Order" );

                    $small_modal.modal( 'hide' );
                }
                else
                {
                    toastr.error( 'Unable to mark as Replaced', " CIRMS | Service Order" );
                }
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        });
    };

    obj.ajax_mark_for_ordering_service_order_by_id = function( $small_modal ){
        var $submit = $('[type="submit"]');

        $.ajax({
            url : 'ajax_service_order/mark_for_ordering_service_order_by_id',
            type : 'post',
            dataType : 'json',
            data : { ref_no : obj.ref_no, user_name : obj.user_name },
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ){
                if( result.status )
                {
                    obj.user_rof_pending_list.ajax.reload();
                    toastr.success( 'Marked as for Ordering!', "CIRMS | Service Order" );

                    $small_modal.modal( 'hide' );
                }
                else
                {
                    toastr.error( 'Unable to process', " CIRMS | Service Order" );
                }
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
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

    function ajax_get_service_order_details_by_ref_no( ref_no ) {
        var $div = $( '#service-order-form' );

        return $.ajax({
            url : 'ajax_service_order/get_service_order_details_by_id',
            type : 'get',
            dataType : 'json',
            data : { ref_no : ref_no },
            success: function( result ) {
                $.each( result, function(index, value) {
                    switch( index ) {
                        case 'if_pulled_out' :
                                if(value == 1) {
                                    $div.find( '[id="if_pulled_out_yes"]' ).attr('checked', 'checked');
                                }
                                else if(value == 0) {
                                    $div.find( '[id="if_pulled_out_no"]' ).attr('checked', 'checked');
                                }
                            break;
                        default :
                            $div.find( '[id=' + index + ']' ).text(value);
                            break;
                    }
                } );
            },
            error : function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    };

    function ajax_get_service_order_details_by_id( $modal ){
        var $field = $modal.find( 'div' ),
            $form = $modal.find( 'form' );
        return $.ajax({
            url : 'ajax_service_order/get_service_order_details_by_id',
            type : 'get',
            dataType : 'json',
            data : { ref_no : obj.ref_no },
            success: function( result ){
                $.each( result, function(index, value){
                    switch( obj.method ){
                        case 'view' :
                            switch( index ) {
                                case 'complaint_type' :
                                    $field.find( '[id=' + index + ']' ).text(value.charAt(0).toUpperCase() + value.slice(1));
                                    break;
                                case 'appoint' :
                                    if( value == null){
                                        $field.find('#designate').remove();
                                    }
                                    break;
                                default :
                                    $field.find( '[id=' + index + ']' ).text(value);
                                    break;
                            }
                            break;
                    }
                } );
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        });
    };

    function load_modal( url, ref_no, cluster_id, complaint_type, method ) {
        var $modal = $( '#large' ),
            $medium_modal = $( '#medium' ),
            $small_modal = $( '#small' ),
            ajax_url = 'modal/' + url;

            obj.method = method;
            obj.ref_no = ref_no;
            obj.cluster_id = cluster_id;
            obj.complaint_type = complaint_type;

        switch( obj.method ){
            case 'view':
                obj.ajax_get_modal_content( ajax_url, $modal ).done( function(){
                    ajax_get_service_order_details_by_id( $modal ).done( function(){
                        $('#service_done_details').hide();
                        if( obj.status === 'replaced' || obj.status === 'all' ){
                            $('#service_done_details').show();
                        }
                        $modal.modal('show');
                    } );
                } );
                break;
            case 'replaced' :
                obj.ajax_get_modal_content( ajax_url, $small_modal ).done( function(){
                    $small_modal.modal( {
                        show : true,
                        backdrop: 'static',
                    } );
                } );
                break;
            case 'ordering' :
                obj.ajax_get_modal_content( ajax_url, $small_modal ).done( function(){
                    $small_modal.modal( {
                        show : true,
                        backdrop: 'static',
                    } );
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

} );