<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_Classroom extends Ajax_Controller {

    public function __construct() {

        # Load Models
        $this->models = array('service_order', 'user', 'cluster', 'classroom', 'computer');

        parent::__construct();

        if(!$this->input->is_ajax_request()) {
            redirect( '403' ); 
        }
    }

    ########################## CRUD ##########################

    public function add_classroom() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->classroom->add_classroom($ajax_data)) {
                $data[ 'status' ] = true;
                $data[ 'access_rights' ] = $this->session->userdata('access_rights');
            }
            else {
                $data[ 'status' ] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_classroom_details() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->classroom->update_classroom_details( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function delete_classroom_by_room_id() {
        $data = array();

        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->classroom->delete_classroom_by_room_id($ajax_data)) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    ########################## ROOM Helper Functions ##########################

    public function get_classroom_details_by_id() {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);
            $data = $this->classroom->get_classroom_details_by_id( $id );
        }

        echo json_encode($data);
    }

    public function is_classroom_no_available() {
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->classroom->is_classroom_no_available($ajax_data)) {
                echo 'true';
            }
            else {
                echo 'false';
            }
        }
    }

    public function is_classroom_no_available_on_update() {
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->classroom->is_classroom_no_available_on_update($ajax_data)) {
                echo 'true';
            }
            else {
                echo 'false';
            }
        }
    }

    public function get_all_classrooms( $query ) {
        $data = array();

        if( $this->input->is_ajax_request() ) {
            $result = $this->classroom->get_all_classrooms( $query );
            $data = is_array($result) ? $result : [];
        }

        echo json_encode($data);
    }

    public function get_classroom_details_by_type($type) {
        $data = array();

        if($this->input->is_ajax_request()) {
            $data = $this->classroom->get_classroom_details_by_type($type);
        }

        echo json_encode($data);
    }

    public function get_all_designation_for_computer() {
        $data = array();

        if($this->input->is_ajax_request()) {
            $data = $this->classroom->get_all_designation_for_computer();
        }

        echo json_encode($data);
    }

    public function get_classroom_details_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->classroom->get_classroom_records_total(),
                'recordsFiltered' => $this->classroom->get_classroom_records_filtered($ajax_data)
            ];

            $data['data'] = $this->classroom->get_classrooms($ajax_data);
        }
        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_classroom_report_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->classroom->get_classroom_report_records_total(),
                'recordsFiltered' => $this->classroom->get_classroom_report_records_filtered($ajax_data),
            ];
            $data['data'] = $this->classroom->get_classroom_report($ajax_data);
        }
        $this->view = FALSE;
        echo json_encode($data);
    }
}
