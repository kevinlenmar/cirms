$( function( $ ) {

    var obj = {};
    init();
    validate();

    obj.ajax_add_computer_resource = function ( $form ){
        var $submit = $form.find( '[type="submit"]' );

        $.ajax( {
            url : 'ajax_computer/add_computer_resource',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status){
                    if(result.access_rights == 'add')
                        toastr.success( "Added!", "CIRMS | Resource" );
                    else
                        toastr.success( "Added! <a role='button' class='btn btn-sm-toastr' href='manage/resources'><i class='fa fa-external-link'></i> View List</a>", "CIRMS | Resource" );
                }
                else{
                    toastr.error( "Failed to add", "CIRMS | Resource" );
                }

                $form.trigger("reset");
                $submit.text( 'Submit' ).prop( 'disabled', false );
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
                // $(element).removeClass( errorClass ).addClass( validClass );
                $(element).removeClass( errorClass );
            },
            errorPlacement: function( erorr, element ){
                erorr.insertAfter(element);
            },
            submitHandler: function(){
                obj.ajax_add_computer_resource ( $form );
                validate();
            }
        });
    }
});