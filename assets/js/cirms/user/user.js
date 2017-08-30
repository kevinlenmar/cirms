$( function( $ ) {
    var obj = {};

    init();
    validate();
    ajax_get_clusters();

    obj.ajax_add_user = function ( $form ){
        var $submit = $form.find( '[type="submit"]' );

        $.ajax( {
            url: 'ajax_user/add_user',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status){
                    if(result.access_rights == 'add')
                        toastr.success( "Added!", "CIRMS | User" );
                    else
                        toastr.success( "Added! <a role='button' class='btn btn-sm-toastr' href='manage/users'><i class='fa fa-external-link'></i> View List</a>", "CIRMS | User" );
                }
                else{
                    toastr.error( "Failed to add", "CIRMS | User" );
                }

                $form.trigger("reset");
                $submit.text( 'Submit' ).prop( 'disabled', false );
                // $('#firstname:first').focus();
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        } );
    }

    function validate(){
        var $form = $('form');

        $form.validate({
            rules: {
                emp_id : {
                    required : true,
                    regex: /^\d+(-\d+)*$/,
                    remote : {
                        url : "ajax_user/is_emp_id_available",
                        type : "post"
                    }
                },
                user_type : {
                    required : true
                },
                firstname : {
                    required : true
                },
                lastname : {
                    required : true
                },
                cluster_id : {
                    required : true
                },
                contact_no : {
                    required : true
                }
            },
            messages : {
                emp_id : {
                    required : "Employee ID is required",
                    regex: "Employee ID must contain numbers and hyphens only",
                    remote : "Employee ID is already registered"
                },
                user_type : {
                    required : "User Type is required"
                },
                firstname : {
                    required : "Firstname is required"
                },
                lastname : {
                    required : "Lastname is required"
                },
                cluster_id : {
                    required : "Cluster is required"
                },
                contact_no : {
                    required : "Contact number is required"
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
                obj.ajax_add_user( $form );
                validate();
            }
        });
    }
});