<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {
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
        if( $this->data['sess_access_rights'] != 'ultimate_control' && $this->data['sess_access_rights'] != 'full_control' ) {
            redirect( '403' );
        }
    }

    public function service_order_report() {
        $this->template = 'includes/layout';

        $this
             // Styles
             ->add_local_styles('assets/css/datatables/datatables-bootstrap')
             ->add_local_styles('assets/css/datatables/buttons.bootstrap', TRUE)
             ->add_local_styles('assets/css/datatables/responsive.bootstrap', TRUE)
             // Scripts
             ->add_local_scripts('assets/js/datatables/datatables-jquery')
             ->add_local_scripts('assets/js/datatables/dataTables.responsive', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.tableTools')
             ->add_local_scripts('assets/js/datatables/datatables-bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.buttons', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.flash', TRUE)
             ->add_local_scripts('assets/js/datatables/jszip', TRUE)
             ->add_local_scripts('assets/js/datatables/pdfmake', TRUE)
             ->add_local_scripts('assets/js/datatables/vfs_fonts')
             ->add_local_scripts('assets/js/datatables/buttons.html5', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.print', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.colVis', TRUE)
             ->add_local_scripts('assets/js/fullscreen/fullscreen')
             ->add_local_scripts('assets/js/cirms/report/service_order_reports');
    }

    public function hardware_report() {
        $this->template = 'includes/layout';

        $this
             // Styles
             ->add_local_styles('assets/css/datatables/datatables-bootstrap')
             ->add_local_styles('assets/css/datatables/buttons.bootstrap', TRUE)
             ->add_local_styles('assets/css/datatables/responsive.bootstrap', TRUE)
             // Scripts
             ->add_local_scripts('assets/js/datatables/datatables-jquery')
             ->add_local_scripts('assets/js/datatables/dataTables.responsive', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.tableTools')
             ->add_local_scripts('assets/js/datatables/datatables-bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.buttons', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.flash', TRUE)
             ->add_local_scripts('assets/js/datatables/jszip', TRUE)
             ->add_local_scripts('assets/js/datatables/pdfmake', TRUE)
             ->add_local_scripts('assets/js/datatables/vfs_fonts')
             ->add_local_scripts('assets/js/datatables/buttons.html5', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.print', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.colVis', TRUE)
             ->add_local_scripts('assets/js/fullscreen/fullscreen')
             ->add_local_scripts('assets/js/cirms/report/hardware_reports');
    }

    public function software_report() {
        $this->template = 'includes/layout';

        $this
             // Styles
             ->add_local_styles('assets/css/datatables/datatables-bootstrap')
             ->add_local_styles('assets/css/datatables/buttons.bootstrap', TRUE)
             ->add_local_styles('assets/css/datatables/responsive.bootstrap', TRUE)
             // Scripts
             ->add_local_scripts('assets/js/datatables/datatables-jquery')
             ->add_local_scripts('assets/js/datatables/dataTables.responsive', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.tableTools')
             ->add_local_scripts('assets/js/datatables/datatables-bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.buttons', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.flash', TRUE)
             ->add_local_scripts('assets/js/datatables/jszip', TRUE)
             ->add_local_scripts('assets/js/datatables/pdfmake', TRUE)
             ->add_local_scripts('assets/js/datatables/vfs_fonts')
             ->add_local_scripts('assets/js/datatables/buttons.html5', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.print', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.colVis', TRUE)
             ->add_local_scripts('assets/js/fullscreen/fullscreen')
             ->add_local_scripts('assets/js/cirms/report/software_reports');
    }

    public function cluster_report() {
        $this->template = 'includes/layout';

        $this->add_local_styles('assets/css/datatables/datatables-bootstrap')
             ->add_local_styles('assets/css/datatables/buttons.bootstrap', TRUE)
             ->add_local_styles('assets/css/datatables/responsive.bootstrap', TRUE);

        $this->add_local_scripts('assets/js/datatables/datatables-jquery')
             ->add_local_scripts('assets/js/datatables/dataTables.responsive', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.tableTools')
             ->add_local_scripts('assets/js/datatables/datatables-bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.buttons', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.flash', TRUE)
             ->add_local_scripts('assets/js/datatables/jszip', TRUE)
             ->add_local_scripts('assets/js/datatables/pdfmake', TRUE)
             ->add_local_scripts('assets/js/datatables/vfs_fonts')
             ->add_local_scripts('assets/js/datatables/buttons.html5', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.print', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.colVis', TRUE)
             ->add_local_scripts('assets/js/fullscreen/fullscreen')
             ->add_local_scripts('assets/js/cirms/report/cluster_reports');
    }

    public function classroom_report() {
        $this->template = 'includes/layout';

        $this->add_local_styles('assets/css/datatables/datatables-bootstrap')
             ->add_local_styles('assets/css/datatables/buttons.bootstrap', TRUE)
             ->add_local_styles('assets/css/datatables/responsive.bootstrap', TRUE);

        $this->add_local_scripts('assets/js/datatables/datatables-jquery')
             ->add_local_scripts('assets/js/datatables/dataTables.responsive', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.tableTools')
             ->add_local_scripts('assets/js/datatables/datatables-bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/dataTables.buttons', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.bootstrap', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.flash', TRUE)
             ->add_local_scripts('assets/js/datatables/jszip', TRUE)
             ->add_local_scripts('assets/js/datatables/pdfmake', TRUE)
             ->add_local_scripts('assets/js/datatables/vfs_fonts')
             ->add_local_scripts('assets/js/datatables/buttons.html5', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.print', TRUE)
             ->add_local_scripts('assets/js/datatables/buttons.colVis', TRUE)
             ->add_local_scripts('assets/js/fullscreen/fullscreen')
             ->add_local_scripts('assets/js/cirms/report/classroom_reports');
    }
}
