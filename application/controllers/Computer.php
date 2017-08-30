<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Computer extends MY_Controller {
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

        if($this->data['sess_access_rights'] !== 'ultimate_control' && $this->data['sess_access_rights'] !== 'full_control' ){
            redirect( '403' );
        }
    }

    public function computer() {
        $this->template = 'includes/layout';

        $this->add_local_scripts('assets/js/cirms/computer/computer');
    }

    public function computer_list() {
        $this->template = 'includes/layout';

        $this
             // Styles
             ->add_local_styles('assets/css/datatables/datatables-bootstrap')
             ->add_local_styles('assets/css/datatables/responsive.bootstrap', TRUE)
             // Scripts
             ->add_local_scripts('assets/js/datatables/datatables-jquery')
             ->add_local_scripts('assets/js/datatables/datatables-bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.responsive', TRUE)

             ->add_local_scripts('assets/js/cirms/computer/computer_list');
    }

    public function computer_resource() {
        $this->template = 'includes/layout';

        $this->add_local_scripts('assets/js/cirms/computer/computer_resource');
    }

    public function computer_resource_list() {
        $this->template = 'includes/layout';

        $this
             // Styles
             ->add_local_styles('assets/css/datatables/datatables-bootstrap')
             ->add_local_styles('assets/css/datatables/responsive.bootstrap', TRUE)
             // Scripts
             ->add_local_scripts('assets/js/datatables/datatables-jquery')
             ->add_local_scripts('assets/js/datatables/datatables-bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.responsive', TRUE)

             ->add_local_scripts('assets/js/cirms/computer/computer_resource_list');
    }
}
