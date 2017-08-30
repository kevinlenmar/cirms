<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Classroom_Model extends MY_Model{
    public function __construct() {
        $this->_types = array('superadmin', 'administrator', 'encoder', 'viewer');

        parent::__construct();
    }

    ######################### CRUD #########################

    public function show_lec_ws() {
        $query = $this->db->select( '*' )
                          ->from( 'computers c' )
                          ->join( 'computer_designation cd', 'cd.computer_id = c.computer_id' )
                          ->join( 'rooms r', 'r.room_no = cd.designation' )
                          ->join( 'classrooms cl', 'cl.room_id = r.room_id' )
                          ->where( 'cl.type', 'e-room' )
                          ->order_by( 'c.computer_name asc' )
                          ->get();

        return $query->result();
    }

    public function show_lab_ws($room_no) {
        $query = $this->db->select( '*' )
                            ->from( 'computers c' )
                            ->join( 'computer_designation cd', 'cd.computer_id = c.computer_id' )
                            ->join( 'rooms r', 'r.room_no = cd.designation' )
                            ->join( 'classrooms cl', 'cl.room_id = r.room_id' )
                            ->where( 'cl.type', 'laboratory' )
                            ->where( 'r.room_no', $room_no )
                            ->order_by( 'c.computer_name asc' )
                            ->get();

        return $query->result();
    }

    public function add_classroom($data) {

        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $rooms = array(
                'room_no'       =>  strtoupper($room_no)
            );
        $this->db->insert('rooms', $rooms);

        # Getting Last inserted ref_no of service order
        $room_id = $this->db->insert_id();

        $classrooms = array(
                'room_id'       =>  $room_id,
                'type'          =>  $classroom_type,
            );

        $this->db->insert('classrooms', $classrooms);


        return $this->db->trans_complete();
    }

    public function update_classroom_details( $data ) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $rooms = array(
                'room_no'       =>  strtoupper($room_no)
            );

        $this->db->where( 'room_id', $room_id )
                 ->update('rooms', $rooms );

        $classrooms = array(
                'room_id'       =>  $room_id,
                'type'          =>  $type,
            );

        $this->db->where( 'room_id', $room_id )
                 ->update('classrooms', $classrooms );


        return ( $this->db->trans_complete());
    }

    public function delete_classroom_by_room_id( $data ) {
        extract( $data );

        $query = $this->db->where( 'room_id', $room_id )
                          ->delete( 'rooms' );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    ######################### Classroom Helper function #########################
    public function get_classroom_details_by_id( $room_id ) {
        $query = $this->db->select( 'r.room_id, r.room_no, cl.type' )
                          ->from( 'rooms r' )
                          ->join( 'classrooms cl', 'cl.room_id = r.room_id', 'inner')
                          ->where( 'r.room_id', $room_id )
                          ->order_by( 'r.room_no asc' )
                          ->get();

        return ( $query->num_rows() ) ? $query->row() : FALSE;
    }


    public function is_classroom_no_available( $data ) {
        extract($data);

        $query = $this->db->where( 'room_no', strtoupper($room_no) )
                          ->get( 'rooms' );

        return ( !$query->num_rows() ) ? TRUE : FALSE;
    }

    public function is_classroom_no_available_on_update( $data ) {
        extract($data);

        $query = $this->db->where( 'room_no', strtoupper($room_no) )
                          ->where('room_id !=', $room_id)
                          ->get( 'rooms' );

        return ( !$query->num_rows() ) ? TRUE : FALSE;
    }

    public function get_all_classrooms($query) {
        $query = urldecode($query);
        $query = $this->db->distinct()
                          ->select( 'room_id, room_no, type' )
                          ->from( 'rooms' )
                          ->like( 'room_no', $query, 'after' )
                          ->order_by( 'room_id asc' )
                          ->get();

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    public function get_all_designation_for_computer() {
        $query = $this->db->select('r.room_id, r.room_no, clr.cluster_code, clr.cluster_name, clr.type, cl.type')
                          ->from('rooms r')
                          ->join('clusters clr', 'clr.room_id = r.room_id', 'left')
                          ->join('classrooms cl', 'cl.room_id = r.room_id', 'left')
                          ->order_by('r.room_no asc')
                          ->get();

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    public function get_classroom_details_by_type($type) {
        $query = $this->db->select('r.room_id, r.room_no, cl.type')
                          ->from('rooms r')
                          ->join('classrooms cl', 'cl.room_id = r.room_id', 'inner')
                          ->where('cl.type', $type)
                          ->order_by('room_no asc')
                          ->get();

        return $query->result();
        // return ($query->num_rows()) ? $query->result() : FALSE;
    }

    ######################### Classroom List function #########################

    public function get_classroom_records_total() {
        $sql = 'SELECT DISTINCT r.room_id, r.room_no, cl.type, cd.designation ';
        $sql .= 'FROM rooms r ';
        $sql .= 'INNER JOIN classrooms cl ON r.room_id = cl.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd ON r.room_no = cd.designation ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_classroom_records_filtered($data) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT r.room_id, r.room_no, cl.type, cd.designation ';
        $sql .= 'FROM rooms r ';
        $sql .= 'INNER JOIN classrooms cl ON r.room_id = cl.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd ON r.room_no = cd.designation ';
        $sql .= 'WHERE 1 ';

        if($type !== 'all'){
            $sql .= 'AND cl.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (r.room_id = ? ';
            $sql .= 'OR r.room_no LIKE ? ';
            $sql .= 'OR cl.type LIKE ?) ';
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

    public function get_classrooms($data, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT r.room_id, r.room_no, cl.type, cd.designation ';
        $sql .= 'FROM rooms r ';
        $sql .= 'INNER JOIN classrooms cl ON r.room_id = cl.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd ON r.room_no = cd.designation ';
        $sql .= 'WHERE 1 ';

        if($type !== 'all'){
            $sql .= 'AND cl.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (r.room_id = ? ';
            $sql .= 'OR r.room_no LIKE ? ';
            $sql .= 'OR cl.type LIKE ?) ';
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

    ################################### Cluster Reports #####################################
    
     public function get_classroom_report_records_total() {
        $sql  = 'SELECT r.room_no, cl.type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END) as no_of_reports_hardware, '; 
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END) as no_of_reports_software, ';
        $sql .= 'COUNT(cr.type) as total_reports_per_cluster ';
        $sql .= 'FROM classrooms cl ';
        $sql .= 'LEFT JOIN rooms r on r.room_id = cl.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = r.room_no ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'UNION ';
        $sql .= 'SELECT "OVERALL TOTAL" room_no, "" type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END), ';
        $sql .= 'COUNT(cr.type) ';
        $sql .= 'FROM classrooms cl ';
        $sql .= 'LEFT JOIN rooms r on r.room_id = cl.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = r.room_no ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_classroom_report_records_filtered($data) {
        extract($data);
        $params = [];

        $sql  = 'SELECT r.room_no, cl.type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END) as no_of_reports_hardware, '; 
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END) as no_of_reports_software, ';
        $sql .= 'COUNT(cr.type) as total_reports_per_cluster ';
        $sql .= 'FROM classrooms cl ';
        $sql .= 'LEFT JOIN rooms r on r.room_id = cl.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = r.room_no ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cl.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (r.room_no LIKE ? ';
            $sql .= 'OR cl.type LIKE ?)';
        }

        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));
        
        if($type !== 'all'){
            $params[] = $type;
        }

        $sql .= 'GROUP BY r.room_no ';

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
        }

        $sql .= 'UNION ';
        $sql .= 'SELECT "OVERALL TOTAL" room_no, "" type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END), ';
        $sql .= 'COUNT(cr.type) ';
        $sql .= 'FROM classrooms cl ';
        $sql .= 'LEFT JOIN rooms r on r.room_id = cl.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = r.room_no ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cl.type = ? ';
        }


        if(!empty($search['value'])){
            $sql .= 'AND (r.room_no LIKE ? ';
            $sql .= 'OR cl.type LIKE ?)';
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
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_classroom_report($data, $details = false) {
        extract($data);
        $params = [];

        $sql  = 'SELECT r.room_no, cl.type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END) as no_of_reports_hardware, '; 
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END) as no_of_reports_software, ';
        $sql .= 'COUNT(cr.type) as total_reports_per_cluster ';
        $sql .= 'FROM classrooms cl ';
        $sql .= 'LEFT JOIN rooms r on r.room_id = cl.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = r.room_no ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cl.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (r.room_no LIKE ? ';
            $sql .= 'OR cl.type LIKE ?)';
        }

        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));
        
        if($type !== 'all'){
            $params[] = $type;
        }

        $sql .= 'GROUP BY r.room_no ';

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
        }

        $sql .= 'UNION ';
        $sql .= 'SELECT "OVERALL TOTAL" room_no, "" type, ';
        $sql .= 'SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END), ';
        $sql .= 'COUNT(cr.type) ';
        $sql .= 'FROM classrooms cl ';
        $sql .= 'LEFT JOIN rooms r on r.room_id = cl.room_id ';
        $sql .= 'LEFT JOIN computer_designation cd on cd.designation = r.room_no ';
        $sql .= 'LEFT JOIN computers c on c.computer_id = cd.computer_id ';
        $sql .= 'LEFT JOIN service_order so on so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN service_order_acceptance soa on soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc on soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cl.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (r.room_no LIKE ? ';
            $sql .= 'OR cl.type LIKE ?)';
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
        }

        $query = $this->db->query($sql, $params);

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

}
