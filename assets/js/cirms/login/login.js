$( function( $ ) {
    var obj = {};
    init();
    validate();
    backstrech_login_background();

    ajax_login = function ( $form ){
        var $submit = $form.find( '[type="submit"]' );
        $.ajax( {
            url : 'ajax_user/login',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Logging in...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status) {
                    if( result.user_status == 'disabled') {
                        toastr.error( "Your account is disabled!", "CIRMS | Login" );
                        $submit.text( 'Sign In' ).prop( 'disabled', false );
                    }
                    else {
                        window.location.href = "dashboard";
                    }
                }
                else {
                    toastr.error( "Invalid Username or Password!", "CIRMS | Login" );
                    $submit.text( 'Sign In' ).prop( 'disabled', false );
                }
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
                    required : true
                },
                password : {
                    required : true
                },
            },
            messages : {
                emp_id : {
                    required : "<span style='font-family: calibri'>Employee ID field is required</span>"
                },
                password : {
                    required : "<span style='font-family: calibri'>Password field is required</span >"
                }

            },
            highlight: function( element, errorClass, validClass ){
                // $(element).addClass( errorClass ).removeClass( validClass );
                return false;
            },
            unhighlight: function( element, errorClass, validClass ){
                // $(element).removeClass( errorClass ).addClass( validClass );
                return false;
            },
            errorPlacement: function( erorr, element ){
                erorr.insertAfter(element);
            },
            submitHandler: function(){
                ajax_login($form);
                validate();
            }
        });
    }

    function backstrech_login_background() {
        $.backstretch("assets/images/backgrounds/CITU.jpg");
    }

});
