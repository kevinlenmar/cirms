$( function( $ ) {
    var obj = {};

    init();
    date_picker(new Date(), '#date_reported', 'top right');
    ajax_get_clusters();
    validate();
    typeahead();
    initialize_received_assigned();
    add_remove_complaint();

    $('#time_reported').timepicker({
        template: false,
        minuteStep: 1,
        showInputs: false
    });

    obj.user_name = $('#user_name').text();
    
    $('.complaint').hide();

    $('#new_complaint').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');
    $( document ).on( 'change', '#complaint_type', function(){
        var value = $(this).val();
        if(value) {
            ajax_get_complaint_by_type('#new_complaint', value).done(function() {
                $("#new_complaint").prop("selectedIndex", 1);
            });
        }
        else {
            $('#new_complaint').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');
        }
    });

    $('#computer_name').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');

    $( document ).on( 'change', '#cluster_id', function(){
        var value = $(this).val();
        if(value) {
            ajax_get_computer(value);
        }
        else {
            $('#computer_name').prop('disabled', false).empty().append('<option default value="">Not yet selected</option>');
        }
    });

    function add_remove_complaint() {
        var addButton = $('.add-button');
        var fieldHTMLTop = '<tr class="complaint2">' +
                            '<td>' +
                                '<div class="form-group">' +
                                    '<div class="col-lg-12">' +
                                        '<div class="input-group" id="complaint-container">';
                                            var fieldHTMLBottom = '<span class="input-group-btn">' +
                                                '<button class="btn btn-danger remove-button" type="button" title="Remove field">' +
                                                    'x' +
                                                '</button>' +
                                            '</span>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</td>' +
                        '</tr>';

        $(addButton).click(function(){
            var ddl = $("#new_complaint").clone();
            ddl.attr("name", "complaint_resource_id[]");

            $(".cirms-table tr.complaint").last().after(fieldHTMLTop);
            $("#complaint-container").append(ddl);
            $("#complaint-container").append(fieldHTMLBottom);
        });

        $(".cirms-table").on('click','.remove-button',function(){
            $(this).closest('tr').remove();
        });
    }

    obj.ajax_add_service_order = function ( $form ){
        var $submit = $form.find( '[type="submit"]' );

        $.ajax( {
            url : 'ajax_service_order/add_service_order/' + obj.user_name,
            type : 'post',
            dataType : 'json',
            data : $form.serialize(),
            beforeSend : function(){
                $submit.text( 'Processing...' ).prop( 'disabled', true );
            },
            success : function( result ) {
                if(result.status){
                    if(result.access_rights === 'add')
                        toastr.success( "Added!", "CIRMS | Service Order" );
                    else
                        toastr.success( "Added! <a role='button' class='btn btn-sm-toastr' href='tasks'><i class='fa fa-external-link'></i> View List</a>", "CIRMS | Service Order" );
                }
                else{
                    toastr.error( "Failed to add", "CIRMS | Service Order" );
                }

                validate();
                $('#emp_id').val('');
                $('#emp_name').val('');
                $('#cluster_id').val('');
                $('#position').val('');
                $('#contact_no').val('');
                $('#computer_name').prop( 'selectedIndex', 0 );
                $('#complaint_type').val('');
                $('#new_complaint').val('');
                $('#complaint_details').val('');
                $('#user_id').val('');
                $('#is_urgent').prop( 'checked', false );
                date_picker(new Date(), '#date_reported', 'top right');
                $submit.text( 'Submit' ).prop( 'disabled', false );
                $('.complaint').hide();
                $('.complaint2').remove();
            },
            error : function( xhr, status ){
                alert( xhr.responseText );
            }
        } );
    };

    function initialize_received_assigned() {
        get_all_user_details_of_admin_encoder('#received_by').done( function() {
            $("#received_by").val($('[name=user_logged_in]').val());
        });
        get_all_user_details_of_admin();
    }

    function typeahead() {
        var users = new Bloodhound({
                                datumTokenizer: Bloodhound.tokenizers.whitespace,
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                remote:{
                                    url: 'ajax_user/get_all_users/%q',
                                    wildcard: '%q'
                                }
                          });

        users.initialize();

        $('#emp_id').typeahead(null,{
            name: 'users',
            source : users,
            displayKey : 'emp_id',
            templates : {
                suggestion : function( user ) {
                    var html = '';

                    html += '<div class="media">';
                        html += '<div class="media-left">';
                            html += '<img class="media-object img-circle padding5" src="assets/images/avatars/default_profile.png" alt="' + user.emp_name + '-avatar">';
                        html += '</div>';
                        html += '<div class="media-body">';
                            html += '<ul class="list-unstyled">';
                            html += '<li><strong class="media-heading">' + user.emp_name + '</strong></li>';
                            html += '<li>' + user.cluster_code + ' - ' + user.cluster_name + '</li>';
                            html += '</ul>';
                         html += '</div>';
                    html += '</div>';

                    return html;
                }
            }
        });

        $('#emp_id').bind('typeahead:select', function( e, suggestion ) {
            var $emp_name = $('#emp_name'),
                $department = $('#cluster_id'),
                $position = $('#position'),
                $contact_no = $('#contact_no');

            $emp_name.val(suggestion.emp_name);
            $department.val(suggestion.cluster_id);
            $position.val(suggestion.position);
            $contact_no.val(suggestion.contact_no);
            ajax_get_computer(suggestion.cluster_id);
        });
        $('#emp_id').bind('typeahead:autocomplete', function( e, suggestion ) {
            var $emp_name = $('#emp_name'),
                $department = $('#cluster_id'),
                $position = $('#position'),
                $contact_no = $('#contact_no');

            $emp_name.val(suggestion.emp_name);
            $department.val(suggestion.cluster_id);
            $contact_no.val(suggestion.contact_no);
            ajax_get_computer(suggestion.cluster_id);

        });
        $('#emp_id').bind('typeahead:cursorchange', function( e, suggestion ) {
            var $emp_name = $('#emp_name');

        });
    }

    function validate(){
        var $form = $('form');

        $form.validate({
            rules: {
                emp_id : {
                    required : true
                },
                emp_name : {
                    required : true
                },
                cluster_id : {
                    required : true
                },
                position : {
                    required : true
                },
                contact_no : {
                    required : true,
                    number : true
                },
                computer_name : {
                    required: true
                },
                complaint_type : {
                    required : true
                },
                'complaint_resource_id[]' : {
                    required : true
                },
                complaint_details : {
                    required : true
                },
                date_reported : {
                    required : true
                },
                time_reported : {
                    required : true
                },
                assigned_to : {
                    required : true
                }
            },
            messages : {
                emp_id : {
                    required : "Employee ID is required"
                },
                emp_name : {
                    required : "Employee ID is required"
                },
                cluster_id : {
                    required : "Cluster is required"
                },
                position : {
                    required : "Position is required"
                },
                contact_no : {
                    required : "Contact number is required",
                    number : "Number only please"
                },
                computer_name : {
                    required: "Computer name is required"
                },
                complaint_type : {
                    required : "Complaint type is required"
                },
                'complaint_resource_id[]' : {
                    required : "Complaint is required"
                },
                complaint_details : {
                    required : "Complaint details is required"
                },
                date_reported : {
                    required : "Date reported is required"
                },
                time_reported : {
                    required : "Time reported is required"
                },
                assigned_to : {
                    required : "Assigned to is required"
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
                obj.ajax_add_service_order( $form );
            }
        });
    }
});
