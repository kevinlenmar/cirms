$( function( $ ) {
    var obj = {};

    obj.loader = '<img src="assets/images/loader/loader.gif" class="loader" />';

    init();

    $( document ).on( 'click', '[data-bind]', function(e){
        e.preventDefault();

        var url = $( this ).data( 'bind' ),
            resource_id = $( this ).data( 'id' ),
            method = $( this ).data( 'method' );

        load_modal( url, resource_id, method );
    } );

    $('[data-role="type"]').click(function(e){
        $(this).addClass('active').siblings().removeClass('active');

        obj.computer_resource_list.ajax.reload();
    });

    $( document ).on( 'click', '#small [type="submit"]', function(e){
        e.preventDefault();
        var $small_modal = $('#small');

        switch(obj.method){
            case 'delete':
                obj.ajax_delete_resource_by_id( $small_modal );
                break;
        }
    });

    obj.computer_resource_list = $('#computer-resource-list').DataTable({
        'processing': true,
        'serverSide': true,
        'responsive': true,
        'ajax': {
            'url': 'ajax_computer/get_computer_resource_details_for_table',
            'data': function ( d ) {
                d.type = $('.active[data-role="type"]').text().toLowerCase();
            }
        },
        'deferRender': true,
        'order': [[0, 'asc']],
        'columns': [
            { 'data': 'resource_id', 'sClass': 'text-center' },
            { 'data': 'resource_name' },
            { 'data': 'type', 'sClass': 'caps' },
            { 'data': 'dummy' },
        ],
        'columnDefs': [
            {
                'data': 'actions',
                'targets': -1,
                'sortable': false,
                'render' : function ( data, type, row ) {
                    var html =  '<div class="text-center"> \
                            <div class="btn-group"> \
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-cirms" data-toggle="dropdown"> \
                                    <i class="fa fa-chevron-down fa-fw"></i> \
                                </button> \
                                <ul class="dropdown-menu pull-right" role="menu"  style="padding: 0 0 7px 0"> \
                                    <li> \
                                        <span class="dropdown-title text-center">Action Bar</span> \
                                    </li> \
                                    <li> \
                                        <a href="javascript:void(0)" data-method="edit" data-bind="edit_computer_resource" data-id="' + row.resource_id + '"><i class="fa fa-edit fa-fw fg-yellow"></i> Edit Entry</a> \
                                    </li>';
                                    if(!row.complaint_resource_id) {
                                        html += '<li> \
                                            <a href="javascript:void(0)" data-method="delete" data-bind="confirmation" data-id="' + row.resource_id + '"><i class="fa fa-trash fa-fw fg-red"></i> Delete Entry</a> \
                                        </li>';
                                    }
                                html += '</ul> \
                            </div> \
                        </div>';

                    return html;
                }
            }
        ],
        'language': {
            "processing": '<div class="processing-wrapper"> \
                                <div><i class="fa fa-spinner fa-spin"></i> Fetching ... Please wait...</div> \
                            </div>',
            'emptyTable': 'No computer_resource(s) available in the database!',
            'zeroRecords': 'No computer_resource found!',
            "infoFiltered": ""
        }
    });


    obj.ajax_get_modal_content = function( ajax_url, $modal ) {
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
    
    obj.ajax_update_computer_resource = function ( $form, $modal ){
        var $submit = $form.find( '[type="submit"]' );
        $.ajax( {
            url: 'ajax_computer/update_computer_resource',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status){
                    obj.computer_resource_list.ajax.reload();
                    toastr.success('Sucessfully updated Resource!', "CIRMS | Resource");

                    $modal.modal( 'hide' );
                }
                else{
                    toastr.error( "Nothing to update", "CIRMS | Resource" );
                }

                $submit.text( 'Update' ).prop( 'disabled', false );
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        } );
    }

    obj.ajax_delete_resource_by_id = function( $small_modal ) {
        $.ajax({
            url : 'ajax_computer/delete_resource_by_id',
            type : 'post',
            dataType : 'json',
            data : { resource_id : obj.resource_id },
            success : function( result ){
                if( result.status ) {
                    obj.computer_resource_list.ajax.reload();
                    toastr.success( 'Removed!', "CIRMS | User" );

                    $small_modal.modal( 'hide' );
                }
                else {
                    toastr.error( 'Unable to delete', " CIRMS | User" );
                }
            },
            error : function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    }

    function ajax_get_computer_resource_details_by_id( $modal ){
        var $field = $modal.find( 'div' ),
            $form = $modal.find( 'form' );

        return $.ajax({
            url : 'ajax_computer/get_computer_resource_details_by_id',
            type : 'get',
            dataType : 'json',
            data : { resource_id : obj.resource_id },
            success: function( result ){
                if( result instanceof Object ) {
                    $.each( result, function(index, value){
                        switch (index) {
                            case 'type' :
                                $form.find( '[name=resource_type]' ).val(value);
                                break;
                            default :
                                $form.find( '[name=' + index + ']' ).val(value);
                                break;
                        }
                    } );
                }
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        });
    };

    function validate( $form, $modal ){
        $form.validate({
            rules: {
                resource_name: {
                    required: true
                },
                resource_type : {
                    required : true
                }
            },
            messages : {
                resource_name: {
                    required: "Resource Name is required"
                },
                resource_type : {
                    required : "Resource Type is required"
                }
            },
            highlight: function( element, errorClass, validClass ){
                $(element).addClass( errorClass ).removeClass( validClass );
            },
            unhighlight: function( element, errorClass, validClass ){
                $(element).removeClass( errorClass );
            },
            errorPlacement: function( erorr, element ){
                erorr.insertAfter(element);
            },
            submitHandler: function(){
                obj.ajax_update_computer_resource( $form, $modal );
            }
        });
    }

    function load_modal( url, resource_id, method ) {
        var $small_modal = $('#small'),
            $medium_modal = $('#medium'),
            ajax_url = 'modal/' + url;

            obj.method = method;
            obj.resource_id = resource_id;

        switch(obj.method) {
            case 'edit' :
                obj.ajax_get_modal_content( ajax_url, $medium_modal ).done( function() {
                    ajax_get_computer_resource_details_by_id( $medium_modal ).done( function() {
                        validate( $medium_modal.find( 'form' ), $medium_modal );
                        $medium_modal.modal( {
                            show : true,
                            backdrop: 'static',
                        });
                    });
                });
                break;
            case 'delete' :
                obj.ajax_get_modal_content( ajax_url, $small_modal ).done( function(){
                    $small_modal.modal( {
                        show : true,
                        backdrop: 'static',
                    });
                });
                break;
        }
    }
});
