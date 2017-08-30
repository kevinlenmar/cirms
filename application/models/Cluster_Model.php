<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Cluster_Model extends MY_Model{
    public function __construct() {
        $this->_types = array('superadmin', 'administrator', 'encoder', 'viewer');

        parent::__construct();
    }

    ######################### CRUD #########################

    public function show_department_ws($cluster_code) {
        $query = $this->db->select( '*' )
                            ->from( 'computers c' )
                            ->join( 'computer_designation cd', 'cd.computer_id = c.computer_id' )
                            ->join(' clusters clr', 'clr.cluster_code = cd.designation', 'left' )
                            ->join(' rooms r', 'r.room_id = clr.room_id', 'inner' )
                            ->where( 'clr.type', 'department' )
                            ->where( 'clr.cluster_code', $cluster_code )
                            ->order_by( 'cd.workstation_no asc' )
                            ->get();

        return $query->result();
    }

    public function show_office_ws($cluster_code) {
        $query = $this->db->select( '*' )
                            ->from( 'computers c' )
                            ->join( 'computer_designation cd', 'cd.computer_id = c.computer_id' )
                            ->join(' clusters clr', 'clr.cluster_code = cd.designation', 'left' )
                            ->join(' rooms r', 'r.room_id = clr.room_id', 'inner' )
                            ->where( 'clr.type', 'office' )
                            ->where( 'clr.cluster_code', $cluster_code )
                            ->order_by( 'cd.workstation_no asc' )
                            ->get();

        return $query->result();
    }

    public function add_cluster($data) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $rooms = array(
                'room_no'          =>  strtoupper($room_no)
            );

        $query = $this->db->insert('rooms', $rooms);

        # Getting Last inserted ref_no of rooms
        $room_id = $this->db->insert_id();

        $clusters = array(
                'room_id'          =>  $room_id,
                'cluster_code'     =>  strtoupper($cluster_code),
                'cluster_name'     =>  ucwords(strtolower($cluster_name)),
                'type'             =>  $type
            );

        $query = $this->db->insert('clusters', $clusters);

        return $this->db->trans_complete();
    }

    public function update_cluster_details( $data ) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $rooms = array(
                'room_no'       =>  strtoupper($room_no)
            );

        $this->db->where( 'room_id', $room_id )
                 ->update('rooms', $rooms );

        $clusters = array(
                'room_id'          =>  $room_id,
                'cluster_code'     =>  strtoupper($cluster_code),
                'cluster_name'     =>  ucwords(strtolower($cluster_name)),
                'type'             =>  $type
            );

        $this->db->where( 'room_id', $room_id )
                 ->update('clusters', $clusters );


        return ( $this->db->trans_complete());
    }

    public function delete_cluster_by_room_id( $data ) {
        extract( $data );

        $query = $this->db->where( 'room_id', $room_id )
                          ->delete( 'rooms' );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    ######################### cluster Helper function #########################

    public function is_cluster_code_available( $data ) {
        extract($data);

        $query = $this->db->where( 'cluster_code', $cluster_code )
                          ->get( 'clusters' );

        return ( !$query->num_rows() ) ? TRUE : FALSE;
    }

    public function is_cluster_code_available_on_update( $data ) {
        extract($data);

        $query = $this->db->where( 'cluster_code', strtoupper($cluster_code) )
                          ->where('room_id !=', $room_id)
                          ->get( 'clusters' );

        return ( !$query->num_rows() ) ? TRUE : FALSE;
    }

    public function get_cluster_details_by_type($type) {
        $query = $this->db->select('r.room_id, r.room_no, clr.cluster_id, clr.cluster_code, clr.cluster_name, clr.type')
                          ->from('rooms r')
                          ->join('clusters clr', 'clr.room_id = r.room_id', 'inner')
                          ->where('clr.type', $type)
                          ->order_by('clr.cluster_code asc')
                          ->get();

        return $query->result();
        // return ($query->num_rows()) ? $query->result() : FALSE;
    }

    public function get_all_cluster_details() {
        $query = $this->db->select('r.room_no, clr.cluster_id, clr.cluster_code, clr.cluster_name, clr.type')
                          ->from('rooms r')
                          ->join('clusters clr', 'clr.room_id = r.room_id', 'inner')
                          ->order_by('clr.cluster_name asc')
                          ->get();

        return $query->result();
        // return ($query->num_rows()) ? $query->result() : FALSE;
    }

    public function get_cluster_details_by_id( $room_id ) {
        $query = $this->db->select( 'r.room_id, r.room_no, clr.cluster_code, clr.cluster_name, clr.type' )
                          ->from( 'rooms r' )
                          ->join( 'clusters clr', 'clr.room_id = r.room_id', 'inner')
                          ->where( 'r.room_id', $room_id )
                          ->order_by('clr.cluster_name asc')
                          ->get();

        return ( $query->num_rows() ) ? $query->row() : FALSE;
    }

    public function get_all_cluster_details_by_id( $cluster_id ) {
        $query = $this->db->select( 'cluster_id, cluster_name, cluster_code' )
                          ->from('clusters' )
                          ->where( 'cluster_id', strtoupper($cluster_id) )
                          ->get();

        return ( $query->num_rows() ) ? $query->row() : FALSE;
    }

    ######################### cluster List function #########################

    public function get_cluster_records_total() {
        $sql = 'SELECT DISTINCT r.room_id, r.room_no, clr.cluster_id, clr.cluster_code, clr.cluster_name, clr.type, cd.designation ';
        $sql .= 'FROM clusters clr ';
        $sql .= 'INNER JOIN rooms r ON clr.room_id = r.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd ON clr.cluster_code = cd.designation ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_cluster_records_filtered($data) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT r.room_id, r.room_no, clr.cluster_id, clr.cluster_code, clr.cluster_name, clr.type, cd.designation ';
        $sql .= 'FROM clusters clr ';
        $sql .= 'INNER JOIN rooms r ON clr.room_id = r.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd ON clr.cluster_code = cd.designation ';
        $sql .= 'WHERE 1 ';

        if($type !== 'all'){
            $sql .= 'AND clr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (cluster_id = ? ';
            $sql .= 'OR room_no LIKE ? ';
            $sql .= 'OR cluster_code LIKE ? ';
            $sql .= 'OR cluster_name LIKE ? ';
            $sql .= 'OR type LIKE ?) ';
        }

        if($type !== 'all'){
            $params[] = $type;
        }

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_clusters($data, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT r.room_id, r.room_no, clr.cluster_id, clr.cluster_code, clr.cluster_name, clr.type, cd.designation ';
        $sql .= 'FROM clusters clr ';
        $sql .= 'INNER JOIN rooms r ON clr.room_id = r.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd ON clr.cluster_code = cd.designation ';
        $sql .= 'WHERE 1 ';

        if($type !== 'all'){
            $sql .= 'AND clr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (cluster_id = ? ';
            $sql .= 'OR room_no LIKE ? ';
            $sql .= 'OR cluster_code LIKE ? ';
            $sql .= 'OR cluster_name LIKE ? ';
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
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $params[] = (int)$start;
        $params[] = (int)$length;


        $query = $this->db->query($sql, $params);

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    ################################### Cluster Reports #####################################
    public function get_cluster_report_records_total() {
        $sql  = 'SELECT clr.cluster_code, clr.cluster_name, clr.type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END) as no_of_reports_hardware, '; 
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END) as no_of_reports_software, ';
        $sql .= 'COUNT(cr.type) as total_reports_per_cluster ';
        $sql .= 'FROM clusters clr ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = clr.cluster_code ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'UNION ';
        $sql .= 'SELECT "OVERALL TOTAL" cluster_code, "" cluster_name, "" type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END), ';
        $sql .= 'COUNT(cr.type) ';
        $sql .= 'FROM clusters clr ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = clr.cluster_code ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
    }

    public function get_custer_report_records_filtered($data) {
        extract($data);
        $params = [];

        $sql  = 'SELECT clr.cluster_code, clr.cluster_name, clr.type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END) as no_of_reports_hardware, '; 
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END) as no_of_reports_software, ';
        $sql .= 'COUNT(cr.type) as total_reports_per_cluster ';
        $sql .= 'FROM clusters clr ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = clr.cluster_code ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND clr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (clr.cluster_code LIKE ? ';
            $sql .= 'OR clr.cluster_name LIKE ? ';
            $sql .= 'OR clr.type LIKE ?)';
        }

        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));
        
        if($type !== 'all'){
            $params[] = $type;
        }

        $sql .= 'GROUP BY clr.cluster_code ';

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        
        $sql .= 'UNION ';
        $sql .= 'SELECT "OVERALL TOTAL" cluster_code, "" cluster_name, "" type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END), ';
        $sql .= 'COUNT(cr.type) ';
        $sql .= 'FROM clusters clr ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = clr.cluster_code ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND clr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (clr.cluster_code LIKE ? ';
            $sql .= 'OR clr.cluster_name LIKE ? ';
            $sql .= 'OR clr.type LIKE ?)';
        }

        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));
        
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

    public function get_cluster_report($data, $details = false) {
        extract($data);
        $params = [];

        $sql  = 'SELECT clr.cluster_code, clr.cluster_name, clr.type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END) as no_of_reports_hardware, '; 
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END) as no_of_reports_software, ';
        $sql .= 'COUNT(cr.type) as total_reports_per_cluster ';
        $sql .= 'FROM clusters clr ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = clr.cluster_code ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND clr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (clr.cluster_code LIKE ? ';
            $sql .= 'OR clr.cluster_name LIKE ? ';
            $sql .= 'OR clr.type LIKE ?)';
        }

        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));
        
        if($type !== 'all'){
            $params[] = $type;
        }

        $sql .= 'GROUP BY clr.cluster_code ';

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        
        $sql .= 'UNION ';
        $sql .= 'SELECT "OVERALL TOTAL" cluster_code, "" cluster_name, "" type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END), ';
        $sql .= 'COUNT(cr.type) ';
        $sql .= 'FROM clusters clr ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = clr.cluster_code ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND clr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (clr.cluster_code LIKE ? ';
            $sql .= 'OR clr.cluster_name LIKE ? ';
            $sql .= 'OR clr.type LIKE ?)';
        }

        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));
        
        if($type !== 'all'){
            $params[] = $type;
        }

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $query = $this->db->query($sql, $params);

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

}
