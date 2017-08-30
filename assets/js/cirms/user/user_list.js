$( function( $ ) {

    var obj = {};

    init();

    obj.loader = '<img src="assets/images/loader/loader.gif" class="loader" />';
    obj.sess_user_type = $('#sess_user_type').text();
    obj.sess_access_rights = $('#sess_access_rights').text();

    $(".prettydate").prettydate();

    $( document ).on( 'click', '[data-bind]', function(e){
        e.preventDefault();

        var url = $( this ).data( 'bind' ),
            user_id = $( this ).data( 'id' ),
            method = $( this ).data( 'method' );

        switch(method) {
            case 'reset':
                obj.ajax_reset_user_pass(user_id);
                break;
            default :
                load_modal( url, user_id, method );
                break;
        }
    } );

    $('[data-role="user_type"]').click(function(e){
        $(this).addClass('active').siblings().removeClass('active');

        obj.user_list.ajax.reload();
    });

    $( document ).on( 'click', '#small [type="submit"]', function(e){
        e.preventDefault();
        var $small_modal = $('#small');

        switch(obj.method){
            case 'delete':
                obj.ajax_delete_user_by_id( $small_modal );
                break;
            case 'disabled':
                obj.ajax_disabled_user_by_id( $small_modal );
                break;
            case 'enabled':
                obj.ajax_enabled_user_by_id( $small_modal );
                break;
            case 'promote':
                obj.ajax_promote_user_by_id( $small_modal );
                break;
            case 'demote':
                obj.ajax_demote_user_by_id( $small_modal );
                break;
        }
    });

    obj.user_list = $('#user-list').DataTable({
        'processing': true,
        'serverSide': true,
        'responsive': true,
        'ajax': {
            'url' : 'ajax_user/get_user_details_for_table',
            'data': function ( d ) {
                d.user_type = $('.active[data-role="user_type"]').text().toLowerCase();
            }
        },
        'deferRender': true,
        'order': [[0, 'asc']],
        'columns': [
            { 'data': 'id', 'sClass': 'text-center' },
            { 
                'data': 'avatar',
                'sortable': false,
                'sClass': 'caps text-center hidden-xs',
                'render': function(data, type, row) {
                    return '<img src="'+data+'" class="user-list-img" />';
                }
            },
            { 'data': 'emp_id' },
            { 'data': 'fullname' },
            { 'data': 'cluster_code' },
            { 'data': 'contact_no' },
            { 'data': 'status', 'sClass': 'caps text-center' },
            { 'data': 'user_type', 'sClass': 'caps text-center' },
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
                                        <a href="#" data-method="view" data-bind="view_user" data-id="' + row.id + '"><i class="fa fa fa-external-link fa-fw fg-cyan"></i> View Details</a> \
                                    </li>';
                                    html += '<li> \
                                        <a href="#" data-method="reset" data-bind="reset_user_pass" data-id="' + row.id + '"><i class="fa fa fa-undo fa-fw fg-green"></i> Reset Password</a> \
                                    </li>'; 
                                    if( row.access_rights === 'ultimate_control' && obj.sess_user_type === 'superadmin'){
                                    html += '<li> \
                                            <a href="#" data-method="demote" data-bind="confirmation" data-id="' + row.id + '"><i class="fa fa-user fa-fw fg-maroon"></i> Demote</a> \
                                        </li>';
                                    }
                                    html += '<li> \
                                        <a href="#" data-method="delete" data-bind="confirmation" data-id="' + row.id + '"><i class="fa fa-trash fa-fw fg-red"></i> Delete Entry</a> \
                                    </li>';
                                    if( (obj.sess_user_type  !== row.user_type && row.access_rights !== 'ultimate_control') || (!row.last_login) ){
                                        if(obj.sess_user_type  !== row.user_type) {
                                            if( obj.sess_access_rights !== row.access_rights || obj.sess_user_type == 'superadmin' ) {
                                                html += '<li> \
                                                    <a href="#" data-method="edit" data-bind="edit_user" data-id="' + row.id + '"><i class="fa fa-edit fa-fw fg-yellow"></i> Edit Entry</a> \
                                                </li>';
                                            }
                                            if( obj.sess_user_type === 'superadmin' && row.access_rights === 'full_control'){
                                                html += '<li> \
                                                    <a href="#" data-method="promote" data-bind="confirmation" data-id="' + row.id + '"><i class="fa fa-user fa-fw fg-maroon"></i> Promote</a> \
                                                </li>';
                                            }
                                            
                                                
                                            
                                            if( ( row.status === 'disabled' && obj.sess_access_rights !== row.access_rights ) || ( row.status === 'disabled' && obj.sess_user_type == 'superadmin' ) ){
                                                html += '<li> \
                                                    <a href="#" data-method="enabled" data-bind="confirmation" data-id="' + row.id + '"><i class="fa fa-check fa-fw fg-darkBlue"></i> Enabled</a> \
                                                </li>';
                                            }
                                            if( ( row.status === 'active' && obj.sess_access_rights !== row.access_rights ) || ( row.status === 'active'  && obj.sess_user_type == 'superadmin' ) ){
                                                html += '<li> \
                                                    <a href="#" data-method="disabled" data-bind="confirmation" data-id="' + row.id + '"><i class="fa fa-remove fa-fw fg-gray"></i> Disabled</a> \
                                                </li>';
                                            }
                                            
                                            
                                        }
                                        
                                           
                                        
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
            'emptyTable': 'No User(s) available in the database!',
            'zeroRecords': 'No User(s) found!',
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

    obj.ajax_delete_user_by_id = function( $small_modal ) {
        $.ajax({
            url: 'ajax_user/delete_user_by_id',
            type : 'post',
            dataType : 'json',
            data : { id : obj.user_id },
            success : function( result ){
                if( result.status ) {
                    obj.user_list.ajax.reload();
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

    obj.ajax_enabled_user_by_id = function( $small_modal ) {
        $.ajax({
            url: 'ajax_user/enabled_user_by_id',
            type : 'post',
            dataType : 'json',
            data : { id : obj.user_id },
            success : function( result ){
                if( result.status ) {
                    obj.user_list.ajax.reload();
                    toastr.success( 'Enabled!', "CIRMS | User" );

                    $small_modal.modal( 'hide' );
                }
                else {
                    toastr.error( 'Unable to enabled', " CIRMS | User" );
                }
            },
            error : function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    }

    obj.ajax_disabled_user_by_id = function( $small_modal ) {
        $.ajax({
            url: 'ajax_user/disabled_user_by_id',
            type : 'post',
            dataType : 'json',
            data : { id : obj.user_id },
            success : function( result ){
                if( result.status ) {
                    obj.user_list.ajax.reload();
                    toastr.success( 'Disabled!', "CIRMS | User" );

                    $small_modal.modal( 'hide' );
                }
                else {
                    toastr.error( 'Unable to disabled', " CIRMS | User" );
                }
            },
            error : function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    }

    obj.ajax_promote_user_by_id = function( $small_modal ) {
        $.ajax({
            url: 'ajax_user/promote_user_by_id',
            type : 'post',
            dataType : 'json',
            data : { id : obj.user_id },
            success : function( result ){
                if( result.status ) {
                    obj.user_list.ajax.reload();
                    toastr.success( 'Sucessfully Promoted!', "CIRMS | User" );

                    $small_modal.modal( 'hide' );
                }
                else {
                    toastr.error( 'Unable to disabled', " CIRMS | User" );
                }
            },
            error : function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    }
    
    obj.ajax_demote_user_by_id = function( $small_modal ) {
        $.ajax({
            url: 'ajax_user/demote_user_by_id', 
            type : 'post',
            dataType : 'json',
            data : { id : obj.user_id },
            success : function( result ){
                if( result.status ) {
                    obj.user_list.ajax.reload();
                    toastr.success( 'Sucessfully Demoted!', "CIRMS | User" );

                    $small_modal.modal( 'hide' );
                }
                else {
                    toastr.error( 'Unable to disabled', " CIRMS | User" );
                }
            },
            error : function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    }
    
    obj.ajax_update_user = function ( $form, $modal ){
        var $submit = $form.find( '[type="submit"]' );
        $.ajax( {
            url: 'ajax_user/update_user',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status){
                    obj.user_list.ajax.reload();
                    toastr.success('Sucessfully updated User!', "CIRMS | User");

                    $modal.modal( 'hide' );
                }
                else{
                    toastr.error( "Nothing to update", "CIRMS | User" );
                }

                $submit.text( 'Update' ).prop( 'disabled', false );
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        } );
    }

    obj.ajax_reset_user_pass = function (user_id) {
        $.ajax( {
            url: 'ajax_user/reset_user_pass',
            type : 'get',
            dataType : 'json',
            data : { id : user_id },
            success : function( result ) {
                if(result.status){
                    obj.user_list.ajax.reload();
                    toastr.success('Password has been reset to <b>123456</b>', "CIRMS | User");

                }
                else{
                    toastr.error( "Unable to reset user password", "CIRMS | User" );
                }

            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        } );
    }

    function ajax_get_user_details_by_id( $modal ){
        var $form = $modal.find( 'form' ),
            $field = $modal.find( 'div' );
        return $.ajax({
            url: 'ajax_user/get_user_details_by_id',
            type : 'get',
            dataType : 'json',
            data : { id : obj.user_id },
            success: function( result ){

                $.each( result, function(index, value){

                    switch( obj.method ){
                        case 'view' :
                            switch( index ){
                                case 'last_login' :
                                    if( value ){
                                        $field.find( '[id=' + index + ']' ).prettydate({
                                                                                autoUpdate: true,
                                                                                date: value,
                                                                                duration: 1000
                                                                            });
                                    }
                                    else {
                                        $field.find( '[id=' + index + ']' ).text('Not yet login');
                                    }
                                    break;
                                case 'date_added' :
                                    $field.find( '[id=' + index + ']' ).text($.format.date(value, "MMMM dd, yyyy"));
                                    break;
                                case 'user_type' :
                                    $field.find( '[id=' + index + ']' ).text(value.toUpperCase());
                                    break
                                case 'access_rights' :
                                    $field.find( '[id=' + index + ']' ).text(value.charAt(0).toUpperCase() + value.slice(1));
                                    $field.find( '[id=' + index + ']' ).each(function() {
                                        var $this = $(this);
                                        $this.text($this.text().replace(/_/g, ' '));
                                    });
                                    break;
                                case 'status' : 
                                    $field.find( '[id=' + index + ']' ).text(value.charAt(0).toUpperCase() + value.slice(1));
                                case 'avatar' :
                                    $( '[id=' + index + ']' ).attr("src", value);
                                    break;
                                default :
                                    if(value && value.length >= 1) {
                                        $field.find( '[id=' + index + ']' ).text(value);
                                    };
                                    break;
                            }
                            break;
                        case 'edit' :
                            switch ( index ){
                                case 'department_id' :
                                    $form.find( '[name=department]' ).val(value);
                                    break;
                                default :
                                    $form.find( '[name=' + index + ']' ).val(value);
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

    function validate( $form, $modal ){
        $form.validate({
            rules: {
                user_type : {
                    required : true
                },
                firstname : {
                    required : true
                },
                lastname : {
                    required : true
                },
                department : {
                    required : true
                },
                contact_no : {
                    required : true
                }
            },
            messages : {
                user_type : {
                    required : "User Type is required"
                },
                firstname : {
                    required : "Firstname is required"
                },
                lastname : {
                    required : "Lastname is required"
                },
                department : {
                    required : "Department is required"
                },
                contact_no : {
                    required : "Contact number is required"
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
                obj.ajax_update_user( $form, $modal );
            }
        });
    }

    function load_modal( url, user_id, method ) {
        var $modal = $( '#large' ),
            $medium_modal = $('#medium'),
            $medium_xs_modal = $('#medium-xs'),
            $small_modal = $('#small'),
            ajax_url = 'modal/' + url;

            obj.method = method;
            obj.user_id = user_id;

        switch(obj.method) {
            case 'view' :
                obj.ajax_get_modal_content( ajax_url, $medium_xs_modal ).done( function() {
                    ajax_get_user_details_by_id( $medium_xs_modal ).done( function(){
                        $medium_xs_modal.modal('show');
                    } );
                } );
                break;
            case 'edit' :
                obj.ajax_get_modal_content( ajax_url, $medium_modal ).done( function() {
                    ajax_get_clusters().done( function() {
                        ajax_get_user_details_by_id( $medium_modal ).done( function() {
                            $medium_modal.modal( {
                                show : true,
                                backdrop: 'static',
                            } );
                            validate( $medium_modal.find( 'form' ), $medium_modal );
                        } );
                    } );
                } );
                break;
            case 'delete' :
                obj.ajax_get_modal_content( ajax_url, $small_modal ).done( function(){
                    $small_modal.modal( {
                        show : true,
                        backdrop: 'static',
                    } );
                } );
                break;
            case 'disabled' :
                obj.ajax_get_modal_content( ajax_url, $small_modal ).done( function(){
                    $small_modal.modal( {
                        show : true,
                        backdrop: 'static',
                    } );
                } );
                break;
            case 'enabled' :
                obj.ajax_get_modal_content( ajax_url, $small_modal ).done( function(){
                    $small_modal.modal( {
                        show : true,
                        backdrop: 'static',
                    } );
                } );
                break;
            case 'promote' :
                obj.ajax_get_modal_content( ajax_url, $small_modal ).done( function(){
                    $small_modal.modal( {
                        show : true,
                        backdrop: 'static',
                    } );
                } );
                break;
            case 'demote' :
                obj.ajax_get_modal_content( ajax_url, $small_modal ).done( function(){
                    $small_modal.modal( {
                        show : true,
                        backdrop: 'static',
                    } );
                } );
                break;   
        }
    }

});
