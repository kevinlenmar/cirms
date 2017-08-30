<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Computer_Model extends MY_Model{
    public function __construct() {
        $this->_types = array('superadmin', 'administrator', 'encoder', 'viewer');

        parent::__construct();
    }

    ######################### CRUD #########################
    public function show_lec_lab_room($room) {
        $query = $this->db->select( '*' )
                            ->from( 'computers c' )
                            ->join( 'computer_designation cd', 'cd.computer_id = c.computer_id' )
                            ->join( 'rooms r', 'r.room_id = cd.designation' )
                            ->where( 'r.type', $type )
                            ->where( 'r.room', $room )
                            ->order_by( 'c.computer_name asc' )
                            ->get();

        return $query->result();
    }

    public function add_computer($data) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $name;

        if( $designation_type === 'laboratory'){
            $lab = str_replace( 'ST', 'L', $designation);
            $name = strtoupper( str_replace( '0', '', $lab) . 'WS' . str_pad((string)($workstation_no), 2, '0', STR_PAD_LEFT) );
        }else if( !empty($computer_name) ){
            $name = strtoupper($computer_name);
        }else{
            $name = strtoupper( $designation . 'WS' . str_pad((string)($workstation_no), 2, '0', STR_PAD_LEFT) );
        }
        

        $computers = array(

                /**
                * # # # Removing ZEROS on designation to fits legacy needs # # #
                *
                * 'computer_name'     =>  strtoupper( (str_replace("0", "", $designation)) .'WS'. str_pad((string)($workstation_no), 2, '0', STR_PAD_LEFT) ),
                *
                * ##############################################################
                **/
                'computer_name'     =>  $name,
                'computer_type'     =>  ucwords(strtolower($computer_type)),
                'brand_clone_name'  =>  ucwords(strtolower($brand_clone_name))
            );

        $this->db->insert('computers', $computers);

        # Getting Last inserted ref_no of service order
        $computer_id = $this->db->insert_id();

        $computer_designation = array(
                'computer_id'           =>  $computer_id,
                'designation_type'      =>  $designation_type,
                'designation'           =>  $designation,
                'workstation_no'        =>  $workstation_no,
                'assigned_to'           =>  ucwords(strtolower($assigned_to)),
                'date_assigned'         =>  date('Y-m-d', strtotime($date_assigned))
            );

        $this->db->insert('computer_designation', $computer_designation);

        return $this->db->trans_complete();
    }

    public function add_computer_resource($data) {
        extract($data);

        $data = array(
                'resource_name'     =>  ucwords(strtolower($resource_name)),
                'type'              =>  strtolower($resource_type)
            );

        $query = $this->db->insert('computer_resources', $data);

        return ($query) ? $this->db->insert_id() : FALSE;
    }

    public function update_computer_details( $data ) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        // Update to computer table
        $computer = array(
                'computer_name'     => ucwords(strtoupper($computer_name)),
                'computer_type'     =>  ucwords(strtolower($computer_type)),
                'brand_clone_name'  =>  ucwords(strtolower($brand_clone_name))
            );
        $this->db->where( 'computer_id', $computer_id )
                 ->update('computers', $computer );

        $computer_designation = array(
                'assigned_to'       =>  ucwords(strtolower($assigned_to)),
                'date_assigned'     =>  date('Y-m-d', strtotime($date_assigned))
            );
        $this->db->where( 'computer_id', $computer_id )
                 ->update('computer_designation', $computer_designation );

        return $this->db->trans_complete();
    }

    public function update_computer_resource($data) {
        extract($data);

        $data = array(
                'resource_name'     =>  ucwords(strtolower($resource_name)),
                'type'              =>  strtolower($resource_type)
            );

        $this->db->where( 'resource_id', $resource_id )
                 ->update('computer_resources', $data );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function delete_computer_by_id( $data ) {
        extract( $data );

        $query = $this->db->where( 'computer_id', $computer_id )
                          ->delete( 'computers' );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function delete_resource_by_id( $data ) {
        extract( $data );

        $query = $this->db->where( 'resource_id', $resource_id )
                          ->delete( 'computer_resources' );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    ######################### Computer Helper function #########################

    public function get_computer_status_by_computer_name($computer_name) {
        $query = $this->db->select('soc.status')
                          ->from('service_order so')
                          ->join('service_order_acceptance soa', 'soa.ref_no = so.ref_no', 'inner')
                          ->join('service_order_completion soc', 'soc.ref_no = so.ref_no', 'inner')
                          ->where('so.computer_name', $computer_name)
                          ->order_by('so.date_added desc')
                          ->limit(1)
                          ->get();
        return $query->row();
    }

    public function is_computer_no_available_for_designation( $data ) {
        extract($data);

        $query = $this->db->where( 'designation', $designation )
                          ->where( 'workstation_no', $workstation_no )
                          ->get( 'computer_designation' );

        return ( !$query->num_rows() ) ? TRUE : FALSE;
    }

    public function is_computer_no_available_on_update( $data ) {
        extract($data);

        $query = $this->db->where( 'workstation_no', $workstation_no )
                          ->where( 'designation', $designation )
                          ->where( 'computer_id !=', $computer_id )
                          ->get( 'computer_designation' );

        return ( !$query->num_rows() ) ? TRUE : FALSE;
    }

    public function get_computer_details_by_id($computer_id) {
        $query = $this->db->select('c.computer_id, c.computer_name, c.computer_type, c.brand_clone_name, cd.designation, cd.workstation_no, cd.assigned_to, cd.date_assigned ')
                          ->from('computers c')
                          ->join('computer_designation cd', 'cd.computer_id = c.computer_id', 'inner')
                          ->where('c.computer_id', $computer_id)
                          ->get();

        return ( $query->num_rows() ) ? $query->row() : FALSE;
    }

    public function get_computer_resource_details_by_id($resource_id) {
        $query = $this->db->select(' resource_id, resource_name, type ')
                          ->from('computer_resources')
                          ->where('resource_id', $resource_id)
                          ->get();
        return ( $query->num_rows() ) ? $query->row() : FALSE;
    }

    public function get_computer_details_for_service_order($cluster_id) {
        $query = $this->db->select('c.computer_id, c.computer_name')
                          ->from('computers c')
                          ->join('computer_designation cd', 'c.computer_id = cd.computer_id', 'inner')
                          ->join('clusters clr', 'cd.designation = clr.cluster_code', 'left')
                          ->where('clr.cluster_id', $cluster_id)
                          ->or_where('cd.designation_type', 'lecture')
                          ->or_where('cd.designation_type', 'e-room')
                          ->or_where('cd.designation_type', 'laboratory')
                          // ->order_by('computer_name')
                          ->get();
        return ($query->num_rows()) ? $query->result() : FALSE;
    }

    public function get_computer_records_total() {
        $sql = 'SELECT DISTINCT c.computer_id, c.computer_name, c.computer_type, c.brand_clone_name, cd.designation, cd.assigned_to, cd.date_assigned,  so.computer_name so_computer_name ';
        $sql .= 'FROM computers c ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'LEFT JOIN service_order so ON so.computer_name = c.computer_name ';
        $sql .= 'WHERE 1 ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_computer_records_filtered($data) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT c.computer_id, c.computer_name, c.computer_type, c.brand_clone_name, cd.designation, cd.assigned_to, cd.date_assigned,  so.computer_name so_computer_name ';
        $sql .= 'FROM computers c ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'LEFT JOIN service_order so ON so.computer_name = c.computer_name ';
        $sql .= 'WHERE 1 ';

        if(!empty($search['value'])){
            $sql .= 'AND (c.computer_id = ? ';
            $sql .= 'OR c.computer_name LIKE ? ';
            $sql .= 'OR c.computer_type LIKE ? ';
            $sql .= 'OR c.brand_clone_name LIKE ? ';
            $sql .= 'OR cd.designation LIKE ? ';
            $sql .= 'OR cd.assigned_to LIKE ? ';
            $sql .= 'OR cd.date_assigned LIKE ?) ';
        }

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_computers($data, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT c.computer_id, c.computer_name, c.computer_type, c.brand_clone_name, cd.designation, cd.assigned_to, cd.date_assigned,  so.computer_name so_computer_name ';
        $sql .= 'FROM computers c ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'LEFT JOIN service_order so ON so.computer_name = c.computer_name ';
        $sql .= 'WHERE 1 ';

        if(!empty($search['value'])){
            $sql .= 'AND (c.computer_id = ? ';
            $sql .= 'OR c.computer_name LIKE ? ';
            $sql .= 'OR c.computer_type LIKE ? ';
            $sql .= 'OR c.brand_clone_name LIKE ? ';
            $sql .= 'OR cd.designation LIKE ? ';
            $sql .= 'OR cd.assigned_to LIKE ? ';
            $sql .= 'OR cd.date_assigned LIKE ?) ';
        }

        if(isset($order)){
            $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';//$order[0]['column']
        }
        $sql .= 'LIMIT ?, ?';

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $params[] = (int)$start;
        $params[] = (int)$length;


        $query = $this->db->query($sql, $params);

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }


    ############ COMPUTER RESOURCE ################

    public function get_computer_resource_records_total() {
        $sql = 'SELECT DISTINCT cr.resource_id, cr.resource_name , cr.type, so.complaint_resource_id ';
        $sql .= 'FROM computer_resources cr ';
        $sql .= 'LEFT JOIN service_order so ON so.complaint_resource_id = cr.resource_id ';
        $sql .= 'WHERE 1 ';
        $sql .= 'AND cr.resource_id != 1 ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_computer_resource_records_filtered($data) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT cr.resource_id, cr.resource_name , cr.type, so.complaint_resource_id ';
        $sql .= 'FROM computer_resources cr ';
        $sql .= 'LEFT JOIN service_order so ON so.complaint_resource_id = cr.resource_id ';
        $sql .= 'WHERE 1 ';
        $sql .= 'AND cr.resource_id != 1 ';

        if($type !== 'all'){
            $sql .= 'AND type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (resource_id = ? ';
            $sql .= 'OR resource_name  LIKE ? ';
            $sql .= 'OR type LIKE ?) ';
        }
        if($type !== 'all'){
            $params[] = $type;
        }

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_computer_resources($data, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT cr.resource_id, cr.resource_name , cr.type, so.complaint_resource_id ';
        $sql .= 'FROM computer_resources cr ';
        $sql .= 'LEFT JOIN service_order so ON so.complaint_resource_id = cr.resource_id ';
        $sql .= 'WHERE 1 ';
        $sql .= 'AND cr.resource_id != 1 ';
        if($type !== 'all'){
            $sql .= 'AND type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (resource_id = ? ';
            $sql .= 'OR resource_name  LIKE ? ';
            $sql .= 'OR type LIKE ?) ';
        }
        if($type !== 'all'){
            $params[] = $type;
        }

        if(isset($order)){
            $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';//$order[0]['column']
        }
        $sql .= 'LIMIT ?, ?';

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $params[] = (int)$start;
        $params[] = (int)$length;


        $query = $this->db->query($sql, $params);

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    ######################## Computer HISTORY ###########################
    public function get_computer_history_by_computer_name( $computer_name ) {
        $query = $this->db->select('c.computer_name, c.computer_type, c.brand_clone_name, cd.designation, cd.assigned_to')
                          ->from('computers c')
                          ->join('computer_designation cd', 'cd.computer_id = c.computer_id', 'inner')
                          ->where('c.computer_name', $computer_name)
                          ->get();

        return ( $query->num_rows() ) ? $query->row() : FALSE;
    }

    public function get_computer_history_records_total($computer_name) {
        $sql = 'SELECT c.computer_name, c.computer_type, c.brand_clone_name , cd.designation, cd.assigned_to, so.ref_no, CONCAT_WS(\': \', cr.resource_name, so.complaint_details) complaint_and_details , soa.date_reported, soc.unit_status ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'INNER JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'WHERE 1 AND c.computer_name = ? ';

        $params[] = $computer_name;

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_computer_history_records_filtered($data, $computer_name) {
        extract($data);
        $params = [];

        $sql = 'SELECT c.computer_name, c.computer_type, c.brand_clone_name , cd.designation, cd.assigned_to, so.ref_no, CONCAT_WS(\': \', cr.resource_name, so.complaint_details) complaint_and_details , soa.date_reported, soc.unit_status ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'INNER JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'WHERE 1 AND c.computer_name = ? ';

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR CONCAT_WS(\': \', cr.resource_name, so.complaint_details) LIKE ? ';
            $sql .= 'OR soa.date_reported LIKE ?)';
        }

        $params[] = $computer_name;

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }
    public function get_computer_history($data, $computer_name, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT c.computer_name, c.computer_type, c.brand_clone_name , cd.designation, cd.assigned_to, so.ref_no, CONCAT_WS(\': \', cr.resource_name, so.complaint_details) complaint_and_details , soa.date_reported, soc.unit_status ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'INNER JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'WHERE 1 AND c.computer_name = ? ';

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR CONCAT_WS(\': \', cr.resource_name, so.complaint_details) LIKE ? ';
            $sql .= 'OR soa.date_reported LIKE ?)';
        }

        if(isset($order)){
            $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';
        }

        $params[] = $computer_name;

        $sql .= 'LIMIT ?, ?';
        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }


        $params[] = (int)$start;
        $params[] = (int)$length;


        $query = $this->db->query($sql, $params);

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }
}
