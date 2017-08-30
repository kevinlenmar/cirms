$( function( $ ) {
    var obj = {};

    init();
    validate('user-info');

    $('#access_rights').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');

    $('#logs-li').click(function() {
        obj.activity_logs.ajax.reload();
    });

    $('#user-info-li').click(function() {
        validate('user-info');
    });

    $('#password-li').click(function() {
        validate('password');
    });

    $( document ).on( 'click', '#activity_logs tbody tr', function(e){
        e.preventDefault();

        var url = 'view_service_order',
            data = obj.activity_logs.row( this ).data();
            obj.ref_no = data['ref_no'];
            obj.method = 'view';

        load_modal( url );
    } );

    $('#clear').click(function(e){
        e.preventDefault();

        var url = 'confirmation';
        obj.method = 'clear';

        load_modal( url );
    });

    $( document ).on( 'click', '#small [type="submit"]', function(e) {
        e.preventDefault();

        var $small_modal = $('#small');

        obj.ajax_clear_activity_logs( $small_modal );
    } );

    $('#access-li').click(function() {
        ajax_get_all_users_details().done( function(){
            $('#user_id').on('change', function() {
                obj.id = this.value;
                if(this.value) {
                    ajax_get_user_details_by_id().done( function(){
                        privileges();
                        ajax_get_user_details_by_id();
                    });
                }
                else {
                    $('#access_rights').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');
                }
            });
        });

        validate('access');
    });

    obj.activity_logs = $('#activity_logs').DataTable({
        'processing': true,
        'serverSide': true,
        'responsive': true,
        'ajax': {
            'url' : 'ajax_user/get_activity_logs_details_for_table'
        },
        'deferRender': true,
        'order': [[0, 'asc']],
        'columns': [
            { 'data': 'ref_no', 'sClass': 'text-center' },
            { 'data': 'computer_name', 'sClass': 'text-center' },
            { 'data': 'activities' },
            { 'data': 'date_added' },
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

    obj.ajax_clear_activity_logs = function( $small_modal ) {
        var $submit = $('[type="submit"]');

        $.ajax({
            url : 'ajax_user/clear_activity_logs',
            type : 'post',
            dataType : 'json',
            beforeSend : function() {
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if( result.status ) {
                    obj.activity_logs.ajax.reload();
                    toastr.success( 'Cleared!', "CIRMS | Activity Logs" );

                    $small_modal.modal( 'hide' );
                }
                else {
                    toastr.error( 'Unable to delete', " CIRMS | Activity Logs" );
                }
            },
            error : function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    }

    obj.ajax_update_access = function ( $form ){
        var $submit = $form.find( '[type="submit"]' );

        $.ajax( {
            url: 'ajax_user/update_user_access',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status) {
                    toastr.success('User access Updated!', "CIRMS | Settings");
                }
                else {
                    toastr.error( "Nothing to update", "CIRMS | Settings");
                }
                $form.trigger("reset");
                $submit.text( 'Save Changes' ).prop( 'disabled', false );
                validate('access');
                $('#access_rights').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');


            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        } );
    }

    obj.ajax_update_user_info = function ( $form ){
        var $submit = $form.find( '[type="submit"]' );

        $.ajax( {
            url: 'ajax_user/update_user_info',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status) {
                    $( '.user-info' ).load( 'dashboard .user-info' );
                    toastr.success('User Info Updated!', "CIRMS | Settings");
                }
                else {
                    toastr.error( "Nothing to update", "CIRMS | Settings");
                }

                $submit.text( 'Save Changes' ).prop( 'disabled', false );
                validate('user-info');

            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        } );
    }

    obj.ajax_update_password = function ( $form ){
        var $submit = $form.find( '[type="submit"]' );

        $.ajax( {
            url: 'ajax_user/update_password',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status) {
                    toastr.success('Password changed!', "CIRMS | Settings");
                }
                else {
                    toastr.error( "Nothing to update", "CIRMS | Settings");
                }

                $form.trigger("reset");
                $submit.text( 'Save Changes' ).prop( 'disabled', false );
                validate('user-info');

            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        } );
    }

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

    function ajax_get_service_order_details_by_id( $modal ) {
        var $field = $modal.find( 'div' );
        return $.ajax({
            url : 'ajax_service_order/get_service_order_details_by_id',
            type : 'get',
            dataType : 'json',
            data : { ref_no : obj.ref_no },
            success: function( result ) {
                $.each( result, function(index, value) {
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
                } );
            },
            error : function( xhr, status ) {
                alert( xhr.responseText );
            }
        });
    };

    function ajax_get_user_details_by_id(){
        var $id = $('#access-rights-tab'),
            $form = $id.find('form');

        return $.ajax({
            url: 'ajax_user/get_user_details_by_id',
            type : 'get',
            dataType : 'json',
            data : { id : obj.id },
            success: function( result ){
                $.each( result, function(index, value){
                    switch(index){
                        case 'user_type':
                            obj.user_type = value;
                            break;
                        default :
                            $form.find( '[name=' + index + ']' ).val(value);
                            break;
                    }
                } );
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        });
    };

    function privileges(){
        var $privileges = $('#access_rights');

        $privileges.empty();

        switch( obj.user_type ){
            case 'administrator':
                var $full_control = $( '<option />' );

                $full_control.attr( 'value', 'full_control' ).text('Full Control');

                $privileges.append( $full_control );
                break;

            case 'encoder':
                var $add = $('<option />'),
                    $add_edit = $('<option />');

                $add.attr( 'value', 'add' ).text('Can Add');
                $add_edit.attr( 'value', 'add_edit' ).text('Can Add and Modify');

                $privileges.append( $add, $add_edit );
                break;

            case 'viewer':
                var $view = $('<option />');

                $view.attr( 'value', 'view' ).text('View Only');

                $privileges.append( $view );
                break;
        }
    }

    function ajax_get_all_users_details () {
        var $user = $( '#user_id' );

        return $.ajax({
            url: 'ajax_user/get_all_user_details',
            type : 'get',
            dataType : 'json',
            beforeSend: function(){
                var $loader = $('<option />');

                $loader.attr('value', '').text('Fetching...').prop('selected', true);

                $user.append( $loader );
            },
            success: function( result ){
                if( result instanceof Array ) {
                    var $select = $('<option />');
                        $select.attr('value', '').text('Select User');

                    $user.empty();
                    $user.append($select);

                    $.each(result, function(index, value) {
                        var $option = $( '<option />' );

                        $option.attr( 'value', result[ index ].id ).text( result[index].fullname + ' (' + result[index].user_type + ')' );
                        $user.append( $option );
                    });
                }
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        });
    }


    function validate(value){
        if(value === 'password') {

            var $id = $('#password-tab'),
                $form = $id.find('form');

            $form.validate({
                rules: {
                    password: {
                        required : true,
                        remote : {
                            url : "ajax_user/check_current_password",
                            type : "post",
                            data: {
                                id: function() {
                                  return $( '[name="id"]' ).val();
                                }
                            }
                        }
                    },
                    new_password: {
                        minlength : 6,
                        required: true,
                        notEqual : '#password'
                    },
                    confirm_password: {
                        required: true,
                        equalTo : '#new_password'
                    }
                },
                messages : {
                    password: {
                        required: "Current password field is required",
                        remote: "Incorrect current password"
                    },
                    new_password: {
                        minlength: "Password must be at least 6 characters",
                        required: "New password field is required",
                        notEqual : "Please choose different password"
                    },
                    confirm_password: {
                        required: "Confirm password field is required",
                        equalTo : "Password does not match"
                    }

                },
                highlight: function( element, errorClass, validClass ){
                    $(element).addClass( errorClass ).removeClass( validClass );
                },
                unhighlight: function( element, errorClass, validClass ){
                    // $(element).removeClass( errorClass ).addClass( validClass );
                    $(element).removeClass( errorClass );
                },
                errorPlacement: function( erorr, element ){
                    erorr.insertAfter(element);
                },
                submitHandler: function(){
                    obj.ajax_update_password( $form );
                }
            });
        }
        else
        if(value === 'user-info') {
            var $id = $('#user-info-tab'),
                $form = $id.find('form');

            $form.validate({
                rules: {
                    firstname: {
                        required : true,
                        lettersonly: true
                    },
                    lastname: {
                        required: true,
                        lettersonly: true
                    },
                    contact_no: {
                        required: true
                    }
                },
                messages : {
                    firstname : {
                        required : "Firstname is required",
                        lettersonly: "Firstname must contain alpa characters only"
                    },
                    lastname : {
                        required : "Lastname is required",
                        lettersonly: "Lastname must contain alpa characters only"
                    },
                    contact_no : {
                        required : "Contact number is required",
                    }

                },
                highlight: function( element, errorClass, validClass ){
                    $(element).addClass( errorClass ).removeClass( validClass );
                },
                unhighlight: function( element, errorClass, validClass ){
                    // $(element).removeClass( errorClass ).addClass( validClass );
                    $(element).removeClass( errorClass );
                },
                errorPlacement: function( erorr, element ){
                    erorr.insertAfter(element);
                },
                submitHandler: function(){
                    obj.ajax_update_user_info( $form );
                }
            });
        }
        else
        if( value === 'access' ){
            var $id = $('#access-rights-tab'),
                $form = $id.find('form');

            $form.validate({
                rules: {
                    user_id: {
                        required : true
                    },
                    access_rights: {
                        required: true
                    }
                },
                messages : {
                    user_id : {
                        required : "User is required"
                    },
                    access_rights : {
                        required : "Privileges is required"
                    }
                },
                highlight: function( element, errorClass, validClass ){
                    $(element).addClass( errorClass ).removeClass( validClass );
                },
                unhighlight: function( element, errorClass, validClass ){
                    // $(element).removeClass( errorClass ).addClass( validClass );
                    $(element).removeClass( errorClass );
                },
                errorPlacement: function( erorr, element ){
                    erorr.insertAfter(element);
                },
                submitHandler: function(){
                    obj.ajax_update_access( $form );
                }
            });
        }
    }

    function load_modal( url ) {
        var $large = $( '#large' ),
            $small_modal = $( '#small' ),
            ajax_url = 'modal/' + url;

            switch(obj.method){
                case 'view':{
                    obj.ajax_get_modal_content( ajax_url, $large ).done( function() {
                        ajax_get_service_order_details_by_id( $large ).done( function() {
                            $large.modal('show');
                        });
                    });
                    break;
                }
                case 'clear':{
                    obj.ajax_get_modal_content( ajax_url, $small_modal ).done( function() {
                        $small_modal.modal( {
                            show : true,
                            backdrop: 'static',
                        });
                    });
                    break;
                }
            }
    }
});