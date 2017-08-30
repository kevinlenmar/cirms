<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_User extends Ajax_Controller {

    public function __construct() {

        # Load Models
        $this->models = array('service_order', 'user', 'cluster', 'classroom', 'computer');

        parent::__construct();

        if(!$this->input->is_ajax_request()) {
            redirect( '403' );
        }

    }

    ########################## Login ##########################

	public function login() {
        $data = array();

        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($user = $this->user->login($ajax_data)) {
                $data['status'] = true;
                $data['user_status'] = $user->status;
            }
            else {
                $data['status'] = false;
            }
        }
        echo json_encode($data);
    }

    ########################## CRUD ##########################

    public function add_user() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {

            extract($ajax_data);

            switch ($user_type) {
                case 'viewer':
                    $access_rights = 'view';
                    break;
                case 'property custodian':
                    $access_rights = 'view';
                    break;
                case 'encoder':
                    $access_rights = 'add';
                    break;
                case 'administrator':
                    $access_rights = 'full_control';
                    break;
            }

            if($this->user->add_user($ajax_data, $access_rights)) {
                $data[ 'status' ] = true;
                $data[ 'access_rights' ] = $this->session->userdata('access_rights');
            }
            else {
                $data[ 'status' ] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_user_avatar() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {

            $name = 'avatar';

            if(!empty($_FILES[$name]['name'])) {

                extract($ajax_data);

                $user = $this->user->get_user_details_by_id( $id );
                $uploads = './assets/';
                $avatars = './assets/images/avatars/';

                if(!is_dir($uploads)) {
                    mkdir($uploads);
                    chmod($uploads, 0755);
                }
                if(is_dir($uploads)) {
                    if(!is_dir($avatars)) {
                        mkdir($avatars);
                        chmod($avatars, 0755);
                    }
                }
                $path = $avatars . $user->emp_id;

                if(is_dir($path)) {
                    $files = glob($path . "/*");

                    if(isset($_FILES[$name]) && $_FILES[$name]['size'] > 0) {
                        foreach($files as $file) {
                            unlink($file); // Delete each file through the loop
                        }
                    }
                }
                else {
                    mkdir($path);
                }

                $config = array(
                                'upload_path'   =>  $path,
                                'allowed_types' =>  'gif|jpg|png|jpeg',
                                'encrypt_name'  =>  true
                            );

                $this->load->library('upload', $config);

                if($this->upload->do_upload($name)) {
                    $upload_data = $this->upload->data();

                    $ajax_data[ 'avatar' ] = ltrim($path . '/' . $upload_data[ 'file_name' ], './');
                }
                else {
                    return $this->upload->display_errors('', '');
                }
            }
            if($this->user->update_user_avatar( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_user() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            extract($ajax_data);

            switch ($user_type) {
                case 'viewer':
                    $access_rights = 'view';
                    break;
                case 'encoder':
                    $access_rights = 'add';
                    break;
                case 'administrator':
                    $access_rights = 'full_control';
                    break;
            }

            if($this->user->update_user( $ajax_data, $access_rights )) {

                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function disabled_user_by_id() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->disabled_user_by_id( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function clear_activity_logs() {
        $data = array();

        if($this->input->is_ajax_request())
        {
            if($this->user->clear_activity_logs())
            {
                $data['status'] = true;
            }
            else{
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function promote_user_by_id() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->promote_user_by_id( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }
    
    public function demote_user_by_id() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->demote_user_by_id( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function enabled_user_by_id() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->enabled_user_by_id( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_user_access() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->update_user_access( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_user_info() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->update_user_info( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_password() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->update_password( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function reset_user_pass() {
        $data = array();

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {

            if($this->user->reset_user_pass( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }


    public function delete_user_by_id() {
        $data = array();

        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->delete_user_by_id($ajax_data)) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    ######################### User Helper function #########################

    public function check_user_if_used() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->check_user_had_received( $ajax_data ) && $this->user->check_user_had_assigned( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function check_current_password() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->check_current_password( $ajax_data )) {
                 echo 'true';
            }
            else {
                echo 'false';
            }
        }
    }

    public function update_pass_alert_status() {

        if($this->input->is_ajax_request()) {
            $data = $this->user->update_pass_alert_status();
        }
        echo json_encode($data);
    }

    public function update_user_sidebar_status() {

        if($this->input->is_ajax_request()) {
            $sidebar_status = 'active';

            if($this->session->userdata('sidebar_status') === 'active') {
                $sidebar_status = '';
            }

            $data = $this->user->update_user_sidebar_status($sidebar_status);
        }

        echo json_encode($data);
    }

    public function is_emp_id_available() {
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->user->is_emp_id_available($ajax_data)) {
                echo 'true';
            }
            else {
                echo 'false';
            }

        }
    }

    public function get_user_details_by_id() {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);
            $data = $this->user->get_user_details_by_id( $id );
        }

        echo json_encode($data);
    }

    public function get_all_users( $query ) {
        $data = array();

        if( $this->input->is_ajax_request() ) {
            $result = $this->user->get_all_users( $query );
            $data = is_array($result) ? $result : [];
        }

        echo json_encode($data);
    }

    public function get_all_user_details() {
        $data = array();

        if($this->input->is_ajax_request()) {
            $data = $this->user->get_all_user_details();
        }

        echo json_encode($data);
    }

    public function get_all_user_details_of_admin_encoder() {
        $data = array();

        if($this->input->is_ajax_request()) {
            $data = $this->user->get_all_user_details_of_admin_encoder();
        }

        echo json_encode($data);
    }

    public function get_all_user_details_of_admin() {
        $data = array();

        if($this->input->is_ajax_request()) {
            $data = $this->user->get_all_user_details_of_admin();
        }

        echo json_encode($data);
    }

    public function get_user_details_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->user->get_users_records_total(),
                'recordsFiltered' => $this->user->get_users_records_filtered( $ajax_data, $user_type )
            ];

            $data['data'] = $this->user->get_users( $ajax_data, $user_type );
        }
        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_activity_logs_details_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->user->get_activity_logs_records_total(),
                'recordsFiltered' => $this->user->get_activity_logs_records_filtered( $ajax_data )
            ];

            $data['data'] = $this->user->get_activity_logs( $ajax_data );
        }
        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_task_details_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->user->get_task_records_total( $this->session->userdata('id') ),
                'recordsFiltered' => $this->user->get_task_records_filtered( $ajax_data, $this->session->userdata('id'), $status )
            ];

            $data['data'] = $this->user->get_task( $ajax_data, $this->session->userdata('id'), $status );
        }
        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_rof_pending_details_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->user->get_rof_pending_records_total(),
                'recordsFiltered' => $this->user->get_rof_pending_records_filtered( $ajax_data, $this->session->userdata('id'), $status )
            ];

            $data['data'] = $this->user->get_rof_pending( $ajax_data, $this->session->userdata('id'), $status );
        }
        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_pending_details_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->user->get_pending_records_total(),
                'recordsFiltered' => $this->user->get_pending_records_filtered( $ajax_data, $this->session->userdata('cluster_id'))
            ];

            $data['data'] = $this->user->get_pending_services( $ajax_data, $this->session->userdata('cluster_id'));
        }
        $this->view = FALSE;
        echo json_encode($data);
    }
}
