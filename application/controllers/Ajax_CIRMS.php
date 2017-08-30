<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Ajax_CIRMS extends Ajax_Controller {	
	
	public function __construct() {
        parent::__construct();

        if(!$this->input->is_ajax_request()) {
            redirect( '403' ); 
        }
    }

	public function modal($modal) {
        $this->load->view('modals/' . $modal );
    }

    public function service_order_form() {
        $this->load->view('includes/service_order_form' );
    }
}
