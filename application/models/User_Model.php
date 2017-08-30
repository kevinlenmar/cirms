<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends MY_Model{

    public function __construct() {
        $this->_types = array('superadmin', 'administrator', 'encoder', 'viewer');

        parent::__construct();
    }

    var $details;

    ######################### Login #########################
    public function set_session() {

        $this->session->set_userdata(
            array (
                'id'                =>  $this->details->id,
                'emp_id'            =>  $this->details->emp_id,
                'firstname'         =>  $this->details->firstname,
                'lastname'          =>  $this->details->lastname,
                'contact_no'        =>  $this->details->contact_no,
                'cluster_id'        =>  $this->details->cluster_id,
                'user_type'         =>  strtolower($this->details->user_type),
                'access_rights'     =>  $this->details->access_rights,
                'avatar'            =>  $this->details->avatar,
                'is_logged_in'      =>  TRUE,
                'sidebar_status'    =>  'active',
                'pass_alert'        =>  FALSE
            )
        );

        $this->user->update_last_login($this->details->id);
    }

    public function login($data) {
        extract($data);

        $status = FALSE;

        if( $user = $this->get_user_info_through_login($data) ) {

            $_user = $user[0];

            if($_user->status == 'active') {
                $this->details = $_user;
                $this->set_session();
            }

            $status = $_user;
        }

        return $status;
    }

    public function get_user_info_through_login($data) {
        extract($data);

        $this->db->from('users')
                 ->where('emp_id',strtoupper($emp_id))
                 ->where('password', sha1($password));

        return $this->db->get()->result();
    }

    public function check_current_password($data) {
        extract($data);

        $this->db->from('users')
                 ->where('id', $id)
                 ->where('password', sha1($password));

        return $this->db->get()->result();
    }

    public function check_if_pass_changed($id) {
        $query = $this->db->where('id', $id)
                      ->get('users');

        $row = $query->row_array();

        return $row['if_pass_changed']; // FALSE = NOT YET CHANGED otherwise CHANGED
    }

    ######################### CRUD #########################


    public function add_user($data , $access_rights) {
        extract($data);

        $data = array(
                'emp_id'        =>  'CIT' . $emp_id,
                'password'      =>  sha1('123456'),
                'firstname'     =>  ucwords(strtolower($firstname)),
                'lastname'      =>  ucwords(strtolower($lastname)),
                'user_type'     =>  $user_type,
                'access_rights' =>  $access_rights,
                'cluster_id'    =>  $cluster_id,
                'contact_no'    =>  $contact_no
            );

        $query = $this->db->insert('users', $data);

        return ($query) ? $this->db->insert_id() : FALSE;
    }

    public function update_user_avatar( $data ) {
        $status = FALSE;
        extract($data);

        $data = array(
                'avatar'    =>  $avatar
            );

        $this->db->where( 'id', $id )
                 ->update('users', $data );

        if($this->db->affected_rows()) {
            $this->session->set_userdata($data);

            $status = TRUE;
        }

        return $status;
    }

    public function update_user( $data, $access_rights ) {
        extract($data);

        $data = array(
                'emp_id'        =>  $emp_id,
                'firstname'     =>  ucwords(strtolower($firstname)),
                'lastname'      =>  ucwords(strtolower($lastname)),
                'user_type'     =>  $user_type,
                'access_rights' =>  $access_rights,
                'cluster_id'    =>  $cluster_id,
                'contact_no'    =>  $contact_no
            );

        $this->db->where( 'id', $id )
                 ->update('users', $data );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function update_user_access( $data ) {
        extract($data);

        $access = array(
                'access_rights'     =>  $access_rights
            );

        $this->db->where( 'id', $user_id )
                 ->update('users', $access );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function update_user_info( $data ) {

        $status = FALSE;
        extract($data);

        $data = array(
                'firstname'     =>  ucwords(strtolower($firstname)),
                'lastname'      =>  ucwords(strtolower($lastname)),
                'contact_no'    =>  $contact_no
            );

        $this->db->where( 'id', $id )
                 ->update('users', $data );

        if($this->db->affected_rows()) {
            $this->session->set_userdata($data);

            $status = TRUE;
        }

        return $status;
    }

    public function update_password( $data ) {
        extract($data);

        $data = array(
                'password'          =>  sha1($confirm_password),
                'if_pass_changed'   =>  TRUE

            );

        $this->db->where( 'id', $id )
                 ->update('users', $data );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function disabled_user_by_id( $data ) {
        extract($data);

        $data = array(
                'status'    =>  'disabled'
            );

        $this->db->where( 'id', $id )
                 ->update('users', $data );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function clear_activity_logs() {

        $this->db->empty_table('logs');

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function promote_user_by_id( $data ) {
        extract($data);

        $data = array(
                'user_type'    =>  'superadmin',
                'access_rights'=>  'ultimate_control'
            );

        $this->db->where( 'id', $id )
                 ->update('users', $data );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }
    
    public function demote_user_by_id( $data ){
        extract($data);
        
        $data = array(
                'user_type' => 'administrator',
                'access_rights' => 'full_control'
            );
        
        $this->db->where( 'id', $id )
                ->update('users', $data );
        
        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function enabled_user_by_id( $data ) {
        extract($data);

        $data = array(
                'status'    =>  'active'
            );

        $this->db->where( 'id', $id )
                 ->update('users', $data );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function reset_user_pass( $data ) {

        extract($data);

        $data = array(
                'password'          => sha1('123456'),
                'if_pass_changed'   => FALSE
            );

        $this->db->where( 'id', $id );

        return $this->db->update('users', $data );
    }


    public function delete_user_by_id( $data ) {
        extract( $data );

        $query = $this->db->where( 'id', $id )
                          ->delete( 'users' );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    ######################### Usesr Helper function #########################

    public function check_user_had_received( $data ) {
        extract($data);

        $query = $this->db->where( 'u.id', $id )
                          ->join('service_order_acceptance soa', 'soa.received_by = u.id')
                          ->get( 'users u' );

        return ( !$query->num_rows() ) ? TRUE : FALSE;
    }

    public function check_user_had_assigned( $data ) {
        extract($data);

        $query = $this->db->where( 'u.id', $id )
                          ->join('service_order_acceptance soa', 'soa.assigned_to = u.id')
                          ->get( 'users u' );

        return ( !$query->num_rows() ) ? TRUE : FALSE;
    }

    public function is_emp_id_available( $data ) {
        extract($data);

        $query = $this->db->where( 'emp_id', 'CIT' . $emp_id )
                          ->get( 'users' );

        return ( !$query->num_rows() ) ? TRUE : FALSE;
    }

    public function update_pass_alert_status($sidebar_status) {

        $data = array(
                'pass_alert'     =>  TRUE   // TRUE = CLOSE else not yet clicked
            );

        $this->session->set_userdata($data);

        return ($sidebar_status != NULL) ? TRUE : FALSE;
    }

    public function update_user_sidebar_status($sidebar_status) {

        $data = array(
                'sidebar_status'     =>  $sidebar_status,
            );

        $this->session->set_userdata($data);

        return ($sidebar_status != NULL) ? TRUE : FALSE;
    }

    public function get_total_users() {
        $query = $this->db->get( 'users' );

        return $query->num_rows();
    }

    public function get_user_details_by_id( $id ) {
    $query = $this->db->select( 'u.id, u.firstname, u.lastname, u.emp_id, u.cluster_id, u.user_type, u.contact_no, u.last_login, u.access_rights, u.date_added, u.avatar, u.status, clr.cluster_code' )
                          ->from( 'users u' )
                          ->join( 'clusters clr', 'clr.cluster_id = u.cluster_id', 'left' )
                          ->where( 'u.id', $id )
                          ->get();

        return ( $query->num_rows() ) ? $query->row() : FALSE;
    }

    public function get_all_users($query) {
        $query = urldecode($query);

        $query = $this->db->distinct()
                          ->select( 'so.emp_id, so.emp_name, so.cluster_id, so.position, so.contact_no, clr.cluster_code, clr.cluster_name' )
                          ->from( 'service_order so' )
                          ->join( 'clusters clr', 'so.cluster_id = clr.cluster_id', 'left')
                          ->like( 'emp_id', $query, 'after' )
                          ->order_by( 'emp_name asc' )
                          ->get();

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    public function get_all_user_details() {

        $query = $this->db->select('id, CONCAT_WS(\' \',firstname, lastname) fullname, user_type')
                          ->from('users')
                          ->not_like('user_type', 'superadmin')
                          ->order_by('user_type')
                          ->order_by('firstname')
                          ->get();

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    public function get_all_user_details_of_admin_encoder() {

        $query = $this->db->select('id, firstname, lastname')
                          ->from('users')
                          ->where('user_type', 'encoder')
                          ->or_where('user_type', 'administrator')
                          ->or_where('user_type', 'superadmin')
                          ->order_by('user_type')
                          ->order_by('firstname')
                          ->get();

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    public function get_all_user_details_of_admin() {

        $query = $this->db->select('id, firstname, lastname')
                          ->from('users')
                          ->where('user_type', 'administrator')
                          ->or_where('user_type', 'superadmin')
                          ->order_by('user_type')
                          ->order_by('firstname')
                          ->get();

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    public function update_last_login($id) {
        $data = array(
                'last_login' => date('Y-m-d H:i:s')
            );

        $this->db->where('id', $id)
                 ->update('users', $data );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    ######################### User's Datas #########################

    public function get_users_records_total() {
        $sql = 'SELECT u.id, u.emp_id, CONCAT_WS(\' \', u.firstname, u.lastname) fullname, clr.cluster_code, u.contact_no, u.user_type, u.avatar, u.status, u.access_rights, u.last_login ';
        $sql .= 'FROM users u ';
        $sql .= 'LEFT JOIN clusters clr ON u.cluster_id = clr.cluster_id ';
        $sql .= 'WHERE 1 ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_users_records_filtered($data) {
        extract($data);
        $params = [];

        $sql = 'SELECT u.id, u.emp_id, CONCAT_WS(\' \', u.firstname, u.lastname) fullname, clr.cluster_code, u.contact_no, u.user_type, u.avatar, u.status, u.access_rights, u.last_login ';
        $sql .= 'FROM users u ';
        $sql .= 'LEFT JOIN clusters clr ON u.cluster_id = clr.cluster_id ';
        $sql .= 'WHERE 1 ';
        if($user_type !== 'all'){
            $sql .= 'AND u.user_type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (u.id LIKE ? OR u.emp_id LIKE ? OR CONCAT_WS(\' \', u.firstname, u.lastname) LIKE ? OR clr.cluster_code LIKE ? OR u.contact_no LIKE ? OR u.user_type LIKE ?)';
        }

        if($user_type !== 'all'){
            $params[] = $user_type;
        }

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_users($data, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT u.id, u.emp_id, CONCAT_WS(\' \', u.firstname, u.lastname) fullname, clr.cluster_code, u.contact_no, u.user_type, u.avatar, u.status, u.access_rights, u.last_login ';
        $sql .= 'FROM users u ';
        $sql .= 'LEFT JOIN clusters clr ON u.cluster_id = clr.cluster_id ';
        $sql .= 'WHERE 1 ';
        if($user_type !== 'all'){
            $sql .= 'AND u.user_type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (u.id LIKE ? OR u.emp_id LIKE ? OR CONCAT_WS(\' \', u.firstname, u.lastname) LIKE ? OR clr.cluster_code LIKE ? OR u.contact_no LIKE ? OR u.user_type LIKE ?)';
        }

        if(isset($order)){
            $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';//$order[0]['column']
        }
        if($user_type !== 'all'){
            $params[] = $user_type;
        }
        $sql .= 'LIMIT ?, ?';

        if(!empty($search['value'])){
            $params[] = $search['value'];
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

    ######################### Activity Logs Datas #########################

    public function get_activity_logs_records_total() {
        $sql = 'SELECT al.id, al.ref_no, al.computer_name, al.activities, al.date_added ';
        $sql .= 'FROM logs al ';
        $sql .= 'WHERE 1 ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_activity_logs_records_filtered($data) {
        extract($data);
        $params = [];

        $sql = 'SELECT al.id, al.ref_no, al.computer_name, al.activities, al.date_added ';
        $sql .= 'FROM logs al ';
        $sql .= 'WHERE 1 ';

        if(!empty($search['value'])){
            $sql .= 'AND (al.ref_no LIKE ? ';
            $sql .= 'OR al.computer_name LIKE ? ';
            $sql .= 'OR al.activities LIKE ? ';
            $sql .= 'OR al.date_added LIKE ?) ';
        }

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_activity_logs($data, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT al.id, al.ref_no, al.computer_name, al.activities, al.date_added ';
        $sql .= 'FROM logs al ';
        $sql .= 'WHERE 1 ';

        if(!empty($search['value'])){
            $sql .= 'AND (al.ref_no LIKE ? ';
            $sql .= 'OR al.computer_name LIKE ? ';
            $sql .= 'OR al.activities LIKE ? ';
            $sql .= 'OR al.date_added LIKE ?) ';
        }

        if(!empty($search['value'])){
            $params[] = $search['value'];
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        if(isset($order)){
            $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';//$order[0]['column']
        }

        $sql .= 'LIMIT ?, ?';

        $params[] = (int)$start;
        $params[] = (int)$length;


        $query = $this->db->query($sql, $params);

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    ######################### Load Task Datas #########################

    public function get_task_records_total($id) {
        $sql = 'SELECT so.ref_no, so.cluster_id, so.computer_name, so.complaint_resource_id, cr.type complaint_type, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported, ';
        $sql .= 'CONCAT_WS(\' \', rb.firstname, rb.lastname) received_by, ';
        $sql .= 'CONCAT_WS(\' \', at.firstname, at.lastname) assigned_to ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN users rb ON soa.received_by = rb.id ';
        $sql .= 'LEFT JOIN users at ON soa.assigned_to = at.id ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_task_records_filtered( $data, $id ) {
        extract($data);
        $params = [];

        $sql = 'SELECT so.ref_no, so.cluster_id, so.computer_name, so.complaint_resource_id, cr.type complaint_type, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported, ';
        $sql .= 'CONCAT_WS(\' \', rb.firstname, rb.lastname) received_by, ';
        $sql .= 'CONCAT_WS(\' \', at.firstname, at.lastname) assigned_to ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN users rb ON soa.received_by = rb.id ';
        $sql .= 'LEFT JOIN users at ON soa.assigned_to = at.id ';
        $sql .= 'WHERE 1 ';
        /*if($user_type != 'superadmin'){
            $sql .= 'AND soa.assigned_to = ? ';

            $params[] = $id;
        }*/

        if($status !== 'all'){
            if($status === 'urgent')
                $sql .= 'AND (so.is_urgent = ? AND soc.status = ?) ';
            else
                $sql .= 'AND soc.status = ? ';
        }
        
        

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.type LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
        }

        if($status !== 'all'){
            if($status === 'urgent'){
                $params[] = TRUE;
                $params[] = 'open';
            }
            else
                $params[] = $status;
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

    public function get_task( $data, $id, $details = false ) {
        extract($data);

        $params = [];

        $sql = 'SELECT so.ref_no, so.cluster_id, so.computer_name, so.complaint_resource_id, cr.type complaint_type, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported, ';
        $sql .= 'CONCAT_WS(\' \', rb.firstname, rb.lastname) received_by, ';
        $sql .= 'CONCAT_WS(\' \', at.firstname, at.lastname) assigned_to ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN users rb ON soa.received_by = rb.id ';
        $sql .= 'LEFT JOIN users at ON soa.assigned_to = at.id ';
        $sql .= 'WHERE 1 ';
        /*if( $user_type != 'superadmin' ){
            $sql .= 'AND soa.assigned_to = ? ';

            $params[] = $id;
        }*/

        if($status !== 'all'){
          if($status === 'urgent')
              $sql .= 'AND (so.is_urgent = ? && soc.status = ?) ';
          else
            $sql .= 'AND soc.status = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.type LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
        }

        if($status !== 'all'){
            if($status === 'urgent'){
                $params[] = TRUE;
                $params[] = 'open';
            }
            else
                $params[] = $status;
        }

        if(isset($order)){
            if( $status === 'open' || $status === 'pending' ){
                $sql .= 'ORDER BY is_urgent DESC, ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';
            }
            else{
                $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';
            }
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

    ###################### Pending Services Table #################################

    public function get_pending_records_total() {
        $sql = 'SELECT so.ref_no, so.computer_name, cr.type complaint_type, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported, soc.unit_status, is_urgent ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';

        $query = $this->db->query( $sql );

        return $query->num_rows();
    }

    public function get_pending_records_filtered( $data, $cluster_id) {
        extract($data);

        $params = [];

        $sql = 'SELECT so.ref_no, so.computer_name, cr.type complaint_type, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported, soc.unit_status, is_urgent ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 ';
        if( $cluster_id != '16' ){
            $sql .= 'AND so.cluster_id = ? ';

            $params[] = $cluster_id;
        }
        
        $sql .= 'AND (soc.status = ? ';
        $sql .= 'OR soc.status = ? ';
        $sql .= 'OR soc.status = ? ';
        $sql .= 'OR soc.status = ?) ';

        $params[] = 'open';
        $params[] = 'pending';
        $params[] = 'replaced';
        $params[] = 'ordering';

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.type LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
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

    public function get_pending_services( $data, $cluster_id) {
        extract($data);

        $params = [];
        $sql = 'SELECT so.ref_no, so.computer_name, cr.type complaint_type, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported, soc.unit_status, is_urgent ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 ';
        if( $cluster_id != '16' ){
            $sql .= 'AND so.cluster_id = ? ';

            $params[] = $cluster_id;
        }
        
        $sql .= 'AND (soc.status = ? ';
        $sql .= 'OR soc.status = ? ';
        $sql .= 'OR soc.status = ? ';
        $sql .= 'OR soc.status = ?) ';

        $params[] = 'open';
        $params[] = 'pending';
        $params[] = 'replaced';
        $params[] = 'ordering';
        

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.type LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
        }

        
        if(isset($order)){
                $sql .= 'ORDER BY is_urgent DESC, ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';
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

    ######################### Load ROF Pending Datas #########################

    public function get_rof_pending_records_total() {
        $sql = 'SELECT so.ref_no, so.cluster_id, so.computer_name, so.complaint_resource_id, cr.type complaint_type, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN users u ON soa.assigned_to = u.id ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_rof_pending_records_filtered( $data, $id ) {
        extract($data);
        $params = [];

        $sql = 'SELECT so.ref_no, so.cluster_id, so.computer_name, so.complaint_resource_id, cr.type complaint_type, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'INNER JOIN users u ON soa.assigned_to = u.id ';
        $sql .= 'WHERE 1 ';

        if($status !== 'all'){
            $sql .= 'AND soc.status = ? ';
        }
        else if($status === 'all'){
            $sql .= ' AND (soc.status = ? || soc.status = ?) ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.type LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
        }

        if($status !== 'all'){
            $params[] = $status;
        }
        else if($status === 'all'){
            $params[] = 'pending';
            $params[] = 'replaced';
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

    public function get_rof_pending( $data, $id, $details = false ) {
        extract($data);

        $params = [];

        $sql = 'SELECT so.ref_no, so.cluster_id, so.computer_name, so.complaint_resource_id, cr.type complaint_type, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'INNER JOIN users u ON soa.assigned_to = u.id ';
        $sql .= 'WHERE 1 ';

        if($status !== 'all'){
            $sql .= 'AND soc.status = ? ';
        }
        else if($status === 'all'){
            $sql .= ' AND (soc.status = ? || soc.status = ?) ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.type LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
        }

        if($status !== 'all'){
            $params[] = $status;
        }
        else if($status === 'all'){
            $params[] = 'pending';
            $params[] = 'replaced';
        }

        if(isset($order)){
            $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';
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
}
