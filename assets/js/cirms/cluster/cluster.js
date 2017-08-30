$( function( $ ) {
    var obj = {};

    init();
    validate();

    obj.ajax_add_cluster = function ( $form ){
        var $submit = $form.find( '[type="submit"]' );

        $.ajax( {
            url : 'ajax_cluster/add_cluster',
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status){
                    $( '.cluster-wrapper-ribbon-cluster' ).load( 'dashboard .cluster-wrapper-ribbon-cluster > li' );
                    $( '.cluster-wrapper-ribbon-classroom' ).load( 'dashboard .cluster-wrapper-ribbon-classroom > li' );
                    $( '.cluster-wrapper-department' ).load( 'dashboard .cluster-wrapper-department > li' );
                    $( '.cluster-wrapper-office' ).load( 'dashboard .cluster-wrapper-office > li' );
                    if(result.access_rights == 'add')
                        toastr.success( "Added!", "CIRMS | Cluster" );
                    else
                        toastr.success( "Added! <a role='button' class='btn btn-sm-toastr' href='manage/clusters'><i class='fa fa-external-link'></i> View List</a>", "CIRMS | Cluster" );
                }
                else{
                    toastr.error( "Failed to add", "CIRMS | Cluster" );
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
                room_no : {
                    required : true,
                    remote : {
                        url : 'ajax_classroom/is_classroom_no_available',
                        type : "post"
                    }
                },
                cluster_code : {
                    required : true,
                    remote : {
                        url : 'ajax_cluster/is_cluster_code_available',
                        type : "post"
                    }
                },
                cluster_name : {
                    required : true

                },
                type : {
                    required : true
                }
            },
            messages : {
                room_no : {
                    required : "Room Number is required",
                    remote : "Room No. is not available"
                },
                cluster_code : {
                    required : "Cluster code is required",
                    remote: "Cluster Code is not available"
                },
                cluster_name : {
                    required : "Cluster Name is required"
                },
                type : {
                    required : "Cluster Type is required"
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
                obj.ajax_add_cluster( $form );
                validate();
            }
        });
    }
});
