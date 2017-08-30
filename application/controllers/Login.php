<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
    public function __construct() {

        # Load Models
        $this->models = array('service_order', 'user', 'cluster', 'classroom', 'computer');

        parent::__construct();

        # Load CIRMS Session Data
        $this->get_session_data();
    }

    public function index() {
        if($this->data['sess_is_logged_in'] ) {
            redirect( 'dashboard' );
        }
        else {
            $this->template = 'includes/login';

            $this
                 // Styles
                 ->add_local_styles('assets/css/cirms/cirms-login') 
                 // Scripts
                 ->add_local_scripts('assets/js/backstretch/jquery.backstretch.min')
                 ->add_local_scripts('assets/js/cirms/login/login');
        }
    }

    public function signout() {
        if(!$this->data['sess_is_logged_in'] ) {
            redirect( base_url() );
        }
        else if( $this->user->update_last_login($this->data['sess_id']) ) {

            $this->session->sess_destroy();
            redirect(base_url());
        }

        $this->view = FALSE;
    }
}
