$( function( $ ) {
    var obj = {};

    obj.loader = '<img src="assets/images/loader/loader.gif" class="loader" />';

    $('[data-bind]').click( function( e ) {
        e.preventDefault();

        var url = $( this ).data( 'bind' ),
            computer_name = $( this ).data( 'name' ),
            method = $( this ).data( 'method' );

        load_modal( url, computer_name, method);
    } );

	obj.ajax_get_modal_content = function( ajax_url, $modal, computer_name, method ) {
        return $.ajax({
            url : ajax_url,
            type : 'get',
            dataType : 'html',
            beforeSend: function(){
                $modal.find( '.modal-content' ).html( obj.loader );
            },
            success: function( response ){
                var html = $( $.parseHTML( response ) ),
                    content = html.filter( '.modal-content' ).html();

                $modal.find( '.modal-content' ).html( content );
            },
            error: function( xhr, status ){
                alert( xhr.responseText );
            }
        });
    }

    function ajax_get_computer_details_for_history( computer_name, method, $modal ) {
        var $form = $modal.find( 'form' ),
            $field = $modal.find( 'div' );

        obj.computer_history = $('#computer-history').DataTable({
            'processing': true,
            'serverSide': true,
            'responsive': true,
            'iDisplayLength': 5,
            'lengthMenu': [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
            'ajax': 'ajax_computer/get_computer_history_details_for_table/' + computer_name,
            'deferRender': true,
            'order': [[0, 'desc']],
            'columns': [
                { 'data': 'ref_no', 'sClass': 'text-center' },
                { 'data': 'complaint_and_details' },
                { 'data': 'unit_status' },
                { 'data': 'date_reported' },
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


        return $.ajax({
            url : 'ajax_computer/get_computer_history_by_computer_name',
            type : 'get',
            dataType : 'json',
            data : { computer_name: computer_name },
            success: function( result ){

                $.each( result, function(index, value) {
                    switch(method) {
                        case 'view' :
                                $field.find( '[id=' + index + ']' ).text(value);
                        break;
                    }
                } );
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        });

    };

	function load_modal( url, computer_name, method ) {
        var $modal = $( '#large' ),
            ajax_url = 'modal/' + url;

        $modal.modal( 'show' );

        obj.ajax_get_modal_content( ajax_url, $modal, computer_name, method ).done( function() {
        	ajax_get_computer_details_for_history( computer_name, method, $modal );
        });
    }
});
