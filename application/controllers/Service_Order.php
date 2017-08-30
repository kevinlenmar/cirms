<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_Order extends MY_Controller {

    public function __construct() {

        # Load Models
        $this->models = array('service_order', 'user', 'cluster', 'classroom', 'computer');

        parent::__construct();

        # Load CIRMS Session Data
        $this->get_session_data();

        # Check if user is currently logged in
        if(!$this->data['sess_is_logged_in']) {
            redirect( base_url() );
        }
    }

    public function service_order() {
        if( $this->data['sess_access_rights'] === 'view' ) {
            redirect( '403' );
        }
        else {
            $this->template = 'includes/layout';

            $this
                 // Styles
                 ->add_local_styles('assets/css/cirms/cirms-typeahead')
                 // Script
                 ->add_local_scripts('assets/js/typeahead/typeahead.bundle', TRUE)
                 ->add_local_scripts('assets/js/cirms/service_order/service-order');
        }
    }

    ### Transfer to task tab ###
    /*public function service_order_list() {
        if($this->data['sess_access_rights'] === 'view' || $this->data['sess_access_rights'] === 'add' ) {
            redirect( '403' );
        }
        else {
            $this->template = 'includes/layout';

            $this
                 // Styles
                 ->add_local_styles('assets/css/datatables/datatables-bootstrap')
                 ->add_local_styles('assets/css/datatables/responsive.bootstrap', TRUE)
                 // Scripts
                 ->add_local_scripts('assets/js/datatables/datatables-jquery')
                 ->add_local_scripts('assets/js/datatables/datatables-bootstrap', TRUE)
                 ->add_local_scripts('assets/js/datatables/dataTables.responsive', TRUE)
                 ->add_local_scripts('assets/js/cirms/service_order/service_order_list');
        }
    }*/
}
