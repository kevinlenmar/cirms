<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends MY_Controller {

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

    public function page_not_found() {
        $this->view = FALSE;
        $data['title'] = 'CIRMS';
        $data['error_header'] = "404";
        $data['error_content'] = "Page not Found";
        $data['error_footer'] = "We're sorry, but the page you were looking for doesn't exist.";
        $this->load->view('pages/error/index', $data);
	}

    public function  access_denied() {
        $this->view = FALSE;
        $data['title'] = 'SKILLS | Access is Denied';
        $data['error_header'] = "403";
        $data['error_content'] = "Access is Denied";
        $data['error_footer'] = "We're sorry, but you do not have sufficient permissions to access this page.";
        $this->load->view('pages/error/index', $data);
    }
}