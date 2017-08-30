<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_Computer extends Ajax_Controller {

    public function __construct() {

        # Load Models
        $this->models = array('service_order', 'user', 'cluster', 'classroom', 'computer');

        parent::__construct();

        if(!$this->input->is_ajax_request()) {
            redirect( '403' ); 
        }

    }

    ########################## CRUD ##########################

    public function add_computer() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->computer->add_computer($ajax_data)) {
                $data[ 'status' ] = true;
                $data[ 'access_rights' ] = $this->session->userdata('access_rights');
            }
            else {
                $data[ 'status' ] = false;
            }
        }

        echo json_encode($data);
    }

    public function add_computer_resource() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->computer->add_computer_resource($ajax_data)) {
                $data[ 'status' ] = true;
            }
            else {
                $data[ 'status' ] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_computer_details() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->computer->update_computer_details( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_computer_resource() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->computer->update_computer_resource( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function delete_computer_by_id() {
        $data = array();

        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->computer->delete_computer_by_id($ajax_data)) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function delete_resource_by_id() {
        $data = array();

        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->computer->delete_resource_by_id($ajax_data)) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    ######################### Computer department function #########################

    public function get_computer_history_by_computer_name() {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);
            $data = $this->computer->get_computer_history_by_computer_name( $computer_name );
        }
        echo json_encode($data);

    }

    public function get_computer_history_details_for_table($computer_name) {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);

             $data = [
                'draw' => $draw,
                'recordsTotal' => $this->computer->get_computer_history_records_total($computer_name),
                'recordsFiltered' => $this->computer->get_computer_history_records_filtered($ajax_data, $computer_name)
            ];

            $data['data'] = $this->computer->get_computer_history($ajax_data, $computer_name);

        }

        $this->view = FALSE;
        echo json_encode($data);

    }

    public function is_computer_no_available_for_designation() {
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->computer->is_computer_no_available_for_designation($ajax_data)) {
                echo 'true';
            }
            else {
                echo 'false';
            }

        }
    }

    public function is_computer_no_available_on_update() {
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->computer->is_computer_no_available_on_update($ajax_data)) {
                echo 'true';
            }
            else {
                echo 'false';
            }

        }
    }

    public function get_computer_details_by_id() {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);
            $data = $this->computer->get_computer_details_by_id( $computer_id );
        }

        echo json_encode($data);
    }

    public function get_computer_resource_details_by_id() {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);
            $data = $this->computer->get_computer_resource_details_by_id( $resource_id );
        }

        echo json_encode($data);
    }

    public function get_computer_details_for_service_order() {
        $data = array();

        if ( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);
            $data = $this->computer->get_computer_details_for_service_order($cluster_id);
        }
        echo json_encode($data);
    }

    public function get_computer_details_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->computer->get_computer_records_total(),
                'recordsFiltered' => $this->computer->get_computer_records_filtered($ajax_data)
            ];

            $data['data'] = $this->computer->get_computers($ajax_data);
        }
        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_computer_resource_details_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->computer->get_computer_resource_records_total(),
                'recordsFiltered' => $this->computer->get_computer_resource_records_filtered($ajax_data)
            ];

            $data['data'] = $this->computer->get_computer_resources($ajax_data);
        }
        $this->view = FALSE;
        echo json_encode($data);
    }
}
