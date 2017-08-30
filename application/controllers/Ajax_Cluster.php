<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_Cluster extends Ajax_Controller {

    public function __construct() {

        # Load Models
        $this->models = array('service_order', 'user', 'cluster', 'classroom', 'computer');

        parent::__construct();

        if(!$this->input->is_ajax_request()) {
            redirect( '403' ); 
        }

    }

    ########################## CRUD ##########################

    public function add_cluster() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->cluster->add_cluster($ajax_data)) {
                $data[ 'status' ] = true;
                $data[ 'access_rights' ] = $this->session->userdata('access_rights');
            }
            else {
                $data[ 'status' ] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_cluster_details() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->cluster->update_cluster_details( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }
        echo json_encode($data);
    }

    public function delete_cluster_by_room_id() {
        $data = array();

        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->cluster->delete_cluster_by_room_id($ajax_data)) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    ######################### Cluster function #########################
    public function is_cluster_code_available() {
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->cluster->is_cluster_code_available($ajax_data)) {
                echo 'true';
            }
            else {
                echo 'false';
            }
        }
    }

    public function is_cluster_code_available_on_update() {
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->cluster->is_cluster_code_available_on_update($ajax_data)) {
                echo 'true';
            }
            else {
                echo 'false';
            }
        }
    }

    public function get_all_cluster_details() {
        $data = array();

        if($this->input->is_ajax_request()) {
            $data = $this->cluster->get_all_cluster_details();
        }

        echo json_encode($data);
    }

    public function get_cluster_details_by_type($type) {
        $data = array();

        if($this->input->is_ajax_request()) {
            $data = $this->cluster->get_cluster_details_by_type($type);
        }

        echo json_encode($data);
    }

    public function get_cluster_details_by_id() {
        $data = array();
        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);
            $data = $this->cluster->get_cluster_details_by_id( $id );
        }

        echo json_encode($data);
    }


    public function get_cluster_details_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->cluster->get_cluster_records_total(),
                'recordsFiltered' => $this->cluster->get_cluster_records_filtered($ajax_data)
            ];

            $data['data'] = $this->cluster->get_clusters($ajax_data);
        }
        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_cluster_report_for_table() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->cluster->get_cluster_report_records_total(),
                'recordsFiltered' => $this->cluster->get_custer_report_records_filtered($ajax_data),
            ];
            $data['data'] = $this->cluster->get_cluster_report($ajax_data);
        }
        $this->view = FALSE;
        echo json_encode($data);
    }
}
