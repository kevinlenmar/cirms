<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_service_order extends Ajax_Controller {

    public function __construct() {

        # Load Models
        $this->models = array('service_order', 'user', 'cluster', 'classroom', 'computer');

        parent::__construct();

        if(!$this->input->is_ajax_request()) {
            redirect( '403' );
        }
    }

    ########################## CRUD ##########################

    public function add_service_order( $user_name ) {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->service_order->add_service_order( $ajax_data, $user_name )) {
                $data[ 'status' ] = true;
                $data[ 'access_rights' ] = $this->session->userdata('access_rights');
            }
            else {
                $data[ 'status' ] = false;
            }
        }

        echo json_encode($data);
    }

    public function show_service_order() {
        $data = array();
        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            if($this->service_order->show_service_order($ajax_data)) {
                $data[ 'status' ] = true;
                echo $this->db->last_query();die;
                $this->data['query'] = $this->service_order->show_service_order($status);
            }
            else {
                $data[ 'status' ] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_service_order( $user_name ) {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->service_order->update_service_order( $ajax_data, $user_name )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function update_service_order_completion( $user_name ) {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            if($unit_status === "need replacement" || $unit_status === 'under warranty'){
                $status = 'pending';
            }
            else {
                $status = 'close';
            }
            if($this->service_order->update_service_order_completion( $ajax_data, $status, $user_name )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function designate_to( $user_name ) {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->service_order->designate_to( $ajax_data, $user_name )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function mark_void_service_order_by_id() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->service_order->mark_void_service_order_by_id( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function mark_replaced_service_order_by_id() {
        $data = array();

        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->service_order->mark_replaced_service_order_by_id( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function mark_for_ordering_service_order_by_id() {
        $data = array();

        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->service_order->mark_for_ordering_service_order_by_id( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function mark_open_service_order_by_id() {
        $data = array();
        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request()) {
            if($this->service_order->mark_open_service_order_by_id( $ajax_data )) {
                $data['status'] = true;
            }
            else {
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    public function delete_service_order_by_id() {
        $data = array();

        if(($ajax_data = $this->input->post()) && $this->input->is_ajax_request())
        {
            if($this->service_order->delete_service_order_by_id($ajax_data))
            {
                $data['status'] = true;
            }
            else{
                $data['status'] = false;
            }
        }

        echo json_encode($data);
    }

    ######################### Service Order service_order function #########################
    public function get_report_graph() {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            $offset = $this->service_order->get_total_report_records_total();
            $data = $this->service_order->get_report_graph($offset);
        }
        echo json_encode($data);
    }

    public function get_report_hardware_software() {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            $offset = $this->service_order->get_total_report_records_total();
            $data = $this->service_order->get_report_hardware_software($offset);
        }

        echo json_encode($data);
    }

    public function get_report_counts_classroom($year) {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            $data = $this->service_order->get_report_counts_classroom($year);
        }

        echo json_encode($data);
    }

    public function get_report_counts_cluster($year) {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request($year) ) {
            $data = $this->service_order->get_report_counts_cluster($year);
        }

        echo json_encode($data);
    }

    public function get_complaint_details($complaint_type) {
        $data = array();

        if($this->input->is_ajax_request()) {
            $data = $this->service_order->get_complaint_details($complaint_type);
        }

        echo json_encode($data);
    }

    public function get_service_order_timeline() {
        $data = array();
        if( ($ajax_data = $this->input->get()) || $this->input->is_ajax_request() ) {
            extract($ajax_data);
            $data = $this->service_order->show_recently_encoded_data( $length );
        }

        echo json_encode($data);
    }

    public function get_service_order_details_by_id() {
        $data = array();

        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);
            $data = $this->service_order->get_service_order_details_by_id( $ref_no );
        }

        echo json_encode($data);
    }

    /*public function get_service_order_by_status() {
        $data = [];

        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->service_order->get_service_orders_records_total(),
                'recordsFiltered' => $this->service_order->get_service_orders_records_filtered($ajax_data, $status),
                'start' => $start
            ];

            $data['data'] = $this->service_order->get_service_orders($ajax_data, $status);

            // $this->dump($this->db->last_query());
        }

        $this->view = FALSE;
        echo json_encode($data);

    }*/

    public function get_report_details_for_table($room_no) {
        $data = array();
        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);

             $data = [
                'draw' => $draw,
                'recordsTotal' => $this->service_order->get_report_details_records_total($ajax_data, $room_no),
                'recordsFiltered' => $this->service_order->get_report_details_records_filtered($ajax_data, $room_no)
            ];

            $data['data'] = $this->service_order->get_report_details($ajax_data, $room_no);

        }

        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_resource_type_report_details_for_table($resource_id) {
        $data = array();
        if( ($ajax_data = $this->input->get()) && $this->input->is_ajax_request() ) {
            extract($ajax_data);

             $data = [
                'draw' => $draw,
                'recordsTotal' => $this->service_order->get_resource_type_report_details_records_total($ajax_data, $resource_id),
                'recordsFiltered' => $this->service_order->get_resource_type_report_details_records_filtered($ajax_data, $resource_id)
            ];

            $data['data'] = $this->service_order->get_resource_type_report_details($ajax_data, $resource_id);

        }

        $this->view = FALSE;
        echo json_encode($data);

    }

    public function get_service_order_done_for_table() {
        $data = [];
        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $total_records = $this->service_order->get_service_orders_done_records_total($type);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $total_records,
                'recordsFiltered' => $this->service_order->get_service_orders_done_records_filtered($ajax_data, $type),
                'start' => $start
            ];

            $data['data'] = $this->service_order->get_service_orders_done($ajax_data, $type, $total_records);
        }

        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_hardware_reports() {
        $data = [];
        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->service_order->get_software_hardware_reports_total(),
                'recordsFiltered' => $this->service_order->get_software_hardware_reports_filtered($ajax_data, 'hardware')
            ];

            $data['data'] = $this->service_order->get_software_hardware_reports($ajax_data, 'hardware');
        }

        $this->view = FALSE;
        echo json_encode($data);
    }

    public function get_software_reports() {
        $data = [];
        if(($ajax_data = $this->input->get()) && $this->input->is_ajax_request()) {
            extract($ajax_data);
            $data = [
                'draw' => $draw,
                'recordsTotal' => $this->service_order->get_software_hardware_reports_total(),
                'recordsFiltered' => $this->service_order->get_software_hardware_reports_filtered($ajax_data, 'software')
            ];

            $data['data'] = $this->service_order->get_software_hardware_reports($ajax_data, 'software');
        }

        $this->view = FALSE;
        echo json_encode($data);
    }

}
