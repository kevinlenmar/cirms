$( function( $ ) {

    var obj = {};
    init();
    date_picker(new Date(), '#date_assigned', 'top right');
    validate();
    $('#designation').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');

    $('#set').click( function(e){
        $('#set_computer_name').removeClass('hidden');
        $('#set_hide').addClass('hidden');
    } );

    $( document ).on( 'change', '#designation_type', function(){
        var value = $(this).val();

        if( value ) {
            switch(value){
                case 'laboratory':
                case 'e-room':
                    ajax_get_classroom_designation_for_computer(value);
                    break;
                case 'department':
                case 'office':
                    ajax_get_cluster_designation_for_computer(value);
                    break;
            }
        }
        else{
            $('#designation').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');
        }
    });

    obj.ajax_add_computer = function ( $form ){
        var $submit = $form.find( '[type="submit"]' );

        $.ajax( {
            url : 'ajax_computer/add_computer',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status){
                    $('#set_computer_name').addClass('hidden');
                    $('#set_hide').removeClass('hidden');

                    if(result.access_rights == 'add')
                        toastr.success( "Added!", "CIRMS | Computer" );
                    else
                        toastr.success( "Added! <a role='button' class='btn btn-sm-toastr' href='manage/computers'><i class='fa fa-external-link'></i> View List</a>", "CIRMS | Computer" );
                    
                }
                else{
                    toastr.error( "Failed to add", "CIRMS | Computer" );
                }

                $form.trigger("reset");
                date_picker(new Date(), '#date_assigned', 'top right');
                $('#designation').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');
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
                computer_type : {
                    required : true
                },
                brand_clone_name : {
                    required : true
                },
                designation_type : {
                    required : true
                },
                designation : {
                    required : true
                }
            },
            messages : {
                computer_type : {
                    required : "Computer type is required"
                },
                brand_clone_name : {
                    required : "Brand / Clone name is required"
                },
                designation: {
                    required : "Designation is required"
                },
                designation_type : {
                    required : "Designation type is required"
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
                obj.ajax_add_computer ( $form );
                validate();
            }
        });
    }
});
