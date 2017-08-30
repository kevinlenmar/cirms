$( function( $ ) {
    var obj = {};

    obj.loader = '<img src="assets/images/loader/loader.gif" class="loader" />';

    init();

    $( '[data-url]').click( function( e ){
        e.preventDefault();

        var url = $( this ).data( 'url' ),
            user_id = $( this ).data( 'id' );

        load_modal( url, user_id );
    } );

    obj.update_user_avatar = function ( $form, $modal ){
        var $submit = $form.find( '[type="submit"]' ),
            formData = new FormData($form[0]);

        $.ajax( {
            url : 'ajax_user/update_user_avatar',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status){
                    $modal.modal('hide');
                    $( '.user-info' ).load( 'dashboard .user-info' );
                    toastr.success( "Profile updated successfully", "CIRMS | User Profile" );
                }
                else{
                    toastr.error( "Failed to update", "CIRMS | User Profile" );
                }

                $submit.text( 'Update' ).prop( 'disabled', false );
            },
            error : function( xhr, status ){
                console.log( xhr.responseText );
            }
        } );
    }

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


    function ajax_get_user_details_by_id( $modal, user_id ){
        var $field = $modal.find( 'div' ),
            $form = $modal.find('form');
        return $.ajax({
            url : 'ajax_user/get_user_details_by_id',
            type : 'get',
            dataType : 'json',
            data : { id : user_id },
            success: function( result ){

                $.each( result, function(index, value){
                    switch(index){
                        case 'last_login':
                            if( value ){
                                $field.find( '[id=' + index + ']' ).text(value);
                            }
                            else {
                                $field.find( '[id=' + index + ']' ).text('Not yet login');
                            }
                            break;
                        case 'date_added' :
                            $field.find( '[id=' + index + ']' ).text($.format.date(value, "MMMM dd, yyyy"));
                            break;
                        case 'user_type':
                            $field.find( '[id=' + index + ']' ).text(value.toUpperCase());
                            break;
                        case 'id' :
                            $form.find( '[id=' + index + ']' ).val(value);
                            break;
                        default :
                            if(value && value.length >= 1) {
                                $field.find( '[id=' + index + ']' ).text(value);
                            };
                            break;
                    }
                } );
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        });
    };

    function load_modal( url, user_id ) {
        var $modal = $( '#medium' ),
            ajax_url = 'modal/' + url;

        obj.ajax_get_modal_content( ajax_url, $modal ).done( function() {
            ajax_get_user_details_by_id( $modal, user_id ).done( function(){
                validate( $modal.find( 'form' ), $modal );
                $modal.modal( {
                    show : true,
                    backdrop: 'static',
                } );
            } )
        } );
    }

    function validate( $form, $modal ){
        if( $.fn.fileinput() ) {
            $("#avatar").fileinput({
                overwriteInitial: true,
                showClose: false,
                showCaption: false,
                browseLabel: '',
                removeLabel: '',
                browseIcon: '<i class="fa fa-upload"></i>',
                browseClass: 'btn btn-primary avatar-custom-upload-icon bg-gold',
                removeIcon: '<i class="fa fa-undo"></i>',
                removeClass: 'btn btn-default avatar-custom-undo-icon',
                removeTitle: 'Cancel or reset changes',
                elErrorContainer: '.avatar-errors',
                msgErrorClass: 'alert alert-block alert-danger',
                showUpload: false,
                initialPreview: [
                   '<img src="' + $('.user-info > img').attr('src') + '" class="file-preview-avatar" alt="Your Avatar">'
                ],
                defaultPreviewContent: '<img src="' + $('.media-avatar > img').attr('src') + '" class="file-preview-avatar" alt="Your Avatar">',
                allowedFileExtensions: ["jpg", "png", "gif", "jpeg"]
            });
        }
        $form.validate({
            rules: {
                avatar : {
                    required : true
                }
            },
            messages : {
                avatar : {
                    required : "Nothing to update"
                }

            },
            highlight: function( element, errorClass, validClass ){
                $(element).addClass( errorClass ).removeClass( validClass );
            },
            unhighlight: function( element, errorClass, validClass ){
                $(element).removeClass( errorClass );
            },
            errorPlacement: function( erorr, element ){
                switch($(element).attr('name')){
                    case 'avatar':
                        erorr.insertAfter('#error');
                        break;
                    default :
                        erorr.insertAfter(element);
                        break;
                }
            },
            submitHandler: function(){
                obj.update_user_avatar( $form, $modal );
            }
        });
    }
});
