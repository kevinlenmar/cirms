<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Service_Order_Model extends MY_Model{
    public function __construct() {
        $this->_types = array('superadmin', 'administrator', 'encoder', 'viewer');

        parent::__construct();
    }

    ######################### CRUD #########################

    public function add_service_order( $data, $user_name ) {
        $if_inverted = array(TRUE, FALSE);

        $color = array(
            'black', 'lime', 'green', 'emerald', 'teal', 'cyan', 'cobalt',
            'indigo', 'violet', 'pink', 'magenta', 'crimson', 'red', 'orange', 'amber',
            'lightOrange','yellow', 'brown', 'olive', 'steel', 'mauve', 'taupe', 'gray',
            'dark', 'darker', 'darkBrown', 'darkCrimson', 'darkMagenta',
            'darkIndigo', 'darkCyan', 'darkCobalt', 'darkTeal', 'darkEmerald', 'darkGreen',
            'darkOrange', 'darkRed', 'darkPink', 'darkViolet', 'darkBlue', 'lightBlue',
            'lightRed', 'lightGreen', 'lighterBlue', 'lightTeal', 'lightOlive', 'lightPink', 'blue'
        );

        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        foreach ($complaint_resource_id as $cri) {
          if($cri) {
            $service_order = array(
                    'emp_id'                =>  $emp_id,
                    'emp_name'              =>  ucwords(strtolower($emp_name)),
                    'cluster_id'            =>  $cluster_id,
                    'position'              =>  ucwords(strtolower($position)),
                    'contact_no'            =>  $contact_no,
                    'complaint_resource_id' =>  $cri,
                    'complaint_details'     =>  ucfirst($complaint_details),
                    'computer_name'         =>  strtoupper($computer_name),
                    'if_pulled_out'         =>  $if_pulled_out,
                    'is_urgent'             =>  $is_urgent
                );

            $this->db->insert('service_order', $service_order);

            # Getting Last inserted ref_no of service order
            $ref_no = $this->db->insert_id();

            $service_order_acceptance = array(
                    'ref_no'                =>  $ref_no,
                    'received_by'           =>  $received_by,
                    'assigned_to'           =>  $assigned_to,
                    'date_reported'         =>  date('Y-m-d', strtotime($date_reported)),
                    'time_reported'         =>  $time_reported
                );

            $this->db->insert('service_order_acceptance', $service_order_acceptance);

            $service_order_completion = array(
                    'ref_no'    => $ref_no
                );

            $this->db->insert('service_order_completion', $service_order_completion);

            $service_order_timeline = array(
                    'ref_no'        => $ref_no,
                    'if_inverted'   => $if_inverted[array_rand($if_inverted)],
                    'color'         => $color[array_rand($color)],
                );

            $this->db->insert('service_order_timeline', $service_order_timeline);

            $activity_log = array(
                    'ref_no'        =>  $ref_no,
                    'computer_name' =>  strtoupper($computer_name),
                    'activities'    =>  str_replace('%20', ' ', $user_name) . ' added new Service Order',
                );
            $this->db->insert('logs', $activity_log);
          }
        }
        return $this->db->trans_complete();
    }

    public function update_service_order( $data, $user_name ) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $service_order = array(
                'emp_id'                =>  $emp_id,
                'emp_name'              =>  ucwords(strtolower($emp_name)),
                'cluster_id'            =>  $cluster_id,
                'position'              =>  ucwords(strtolower($position)),
                'contact_no'            =>  $contact_no,
                'computer_name'         =>  strtoupper($computer_name),
                'if_pulled_out'         =>  $if_pulled_out,
                'complaint_resource_id' =>  $complaint_resource_id,
                'complaint_details'     =>  ucfirst($complaint_details)
            );

        $this->db->where( 'ref_no', $ref_no )
                 ->update( 'service_order', $service_order );

        $service_order_acceptance = array(
                'date_reported'     =>  date('Y-m-d', strtotime($date_reported)),
                'received_by'       =>  $received_by,
                'assigned_to'       =>  $assigned_to,
                'time_reported'     =>  $time_reported
            );

        $this->db->where( 'ref_no', $ref_no )
                 ->update( 'service_order_acceptance', $service_order_acceptance );

        $activity_log = array(
                'ref_no'        =>  $ref_no,
                'computer_name' =>  strtoupper($computer_name),
                'activities'    =>  str_replace('%20', ' ', $user_name) . ' edited a service order',
            );
        $this->db->insert('logs', $activity_log);

        return $this->db->trans_complete();
    }

    public function update_service_order_completion( $data, $status, $user_name ) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $data = array(
                'date_finished'         =>  date('Y-m-d', strtotime($date_finished)),
                'time_finished'         =>  $time_finished,
                'date_replaced'         =>  ( $date_replaced ? date('Y-m-d', strtotime($date_replaced)) : NULL ),
                'time_replaced'         =>  ( $time_replaced ? $time_replaced : NULL ),
                'action_taken'          =>  $action_taken,
                'completed_by'          =>  $completed_by,
                'unit_status'           =>  $unit_status,
                'returned_to'           =>  ( $returned_to ? ucwords(strtolower($returned_to)) : NULL ),
                'property_clerk'        =>  ( $property_clerk ? ucwords(strtolower($property_clerk)) : NULL ),
                // 'property_date_received'=>  ( $property_date_received ? date('Y-m-d', strtotime($property_date_received)) : NULL ),
                'status'                =>  $status
            );

        $this->db->where( 'ref_no', $ref_no )
                 ->update( 'service_order_completion', $data );

        if( $status === 'pending' ){
            $activities = str_replace('%20', ' ', $user_name) . ' forwarded a service order to property';
        }else{
            $activities = str_replace('%20', ' ', $user_name) . ' completed a service order';
        }

        $activity_log = array(
            'ref_no'        =>  $ref_no,
            'computer_name' =>  strtoupper($computer_name),
            'activities'    =>  $activities
            );

        $this->db->insert('logs', $activity_log);

        return $this->db->trans_complete();
    }

    public function designate_to( $data, $user_name ) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $data = array(
                'designate_to'  =>  $designate_to
            );

        $this->db->where( 'ref_no', $ref_no )
                 ->update( 'service_order_acceptance', $data );

        $activities = str_replace('%20', ' ', $user_name) . ' designate a service order to ' . str_replace('%20', ' ', $designate);

        $activity_log = array(
            'ref_no'        =>  $ref_no,
            'computer_name' =>  strtoupper($computer_name),
            'activities'    =>  $activities
            );

        $this->db->insert('logs', $activity_log);

        return $this->db->trans_complete();
    }

    public function mark_void_service_order_by_id( $data ) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $data = array(
                'unit_status'   =>  'void',
                'status'        =>  'void'
            );

        $this->db->where( 'ref_no', $ref_no )
                 ->update( 'service_order_completion', $data );

        $activity_log = array(
                'ref_no'        =>  $ref_no,
                'computer_name' =>  strtoupper($computer_name),
                'activities'    =>  $name . ' voided a service order',
            );
        $this->db->insert('logs', $activity_log);

        return $this->db->trans_complete();
    }

    public function mark_replaced_service_order_by_id( $data ) {
        extract($data);

        $data = array(
                'property_date_received'    =>  date('Y-m-d'),
                'status'                    =>  'replaced',
                'unit_status'               =>  'replaced'
            );

        $this->db->where( 'ref_no', $ref_no )
                 ->update( 'service_order_completion', $data );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function mark_for_ordering_service_order_by_id( $data ) {
        extract($data);

        $data = array(
                'property_clerk'            =>  $user_name,
                'property_date_received'    =>  date('Y-m-d'),
                'status'                    =>  'ordering',
                'unit_status'               =>  'for ordering'
            );

        $this->db->where( 'ref_no', $ref_no )
                 ->update( 'service_order_completion', $data );

        return ( $this->db->affected_rows() ) ? TRUE : FALSE;
    }

    public function mark_open_service_order_by_id( $data ) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract($data);

        $data = array(
                'status'                  =>  'open',
                'date_replaced'           =>  NULL,
                'time_replaced'           =>  NULL,
                'action_taken'            =>  NULL,
                'date_finished'           =>  NULL,
                'time_finished'           =>  NULL,
                'completed_by'            =>  NULL,
                'returned_to'             =>  NULL,
                'property_clerk'          =>  NULL,
                'property_date_received'  =>  NULL,
                'unit_status'             =>  'under repair'
            );

        $this->db->where( 'ref_no', $ref_no )
                 ->update( 'service_order_completion', $data );

        $activity_log = array(
                'ref_no'        =>  $ref_no,
                'computer_name' =>  strtoupper($computer_name),
                'activities'    =>  $name . ' Re-open a service order',
            );
        $this->db->insert('logs', $activity_log);

        return $this->db->trans_complete();
    }

    public function delete_service_order_by_id( $data ) {
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        extract( $data );

        $query = $this->db->where( 'ref_no', $ref_no )
                          ->delete( 'service_order' );

        $activity_log = array(
                'ref_no'        =>  $ref_no,
                'computer_name' =>  strtoupper($computer_name),
                'activities'    =>  $name . ' deleted a service order',
            );
        $this->db->insert('logs', $activity_log);

        return $this->db->trans_complete();
    }

    ######################### Service Order Helper function #########################
    public function get_total_report_records_total() {
        $query = $this->db->select('DATE_FORMAT(soa.date_reported, "%b %Y")')
                          ->from('service_order so')
                          ->join('service_order_acceptance soa', 'so.ref_no = soa.ref_no')
                          ->group_by('DATE_FORMAT(soa.date_reported, "%M %Y")')
                          ->where('DATE_FORMAT(soa.date_reported, "%Y") >=', (date('Y') - 1) )
                          ->get();
        return ($query->num_rows() > 12 ) ? $query->num_rows() - 12 : 0;
    }
    public function get_report_graph($offset) {
        $query = $this->db->select('DATE_FORMAT(soa.date_reported, "%b %Y") as month_year_reported, COUNT(so.ref_no) as no_of_reports', false)
                          ->from('service_order so')
                          ->join('service_order_acceptance soa', 'so.ref_no = soa.ref_no')
                          ->join('service_order_completion soc', 'so.ref_no = soc.ref_no')
                          ->where('soc.status', 'close')
                          ->group_by('DATE_FORMAT(soa.date_reported, "%M %Y")')
                          ->order_by('soa.date_reported asc')
                          ->limit(12, $offset)
                          ->get();
        return ($query->num_rows()) ? $query->result() : FALSE;
    }

    public function get_report_hardware_software($offset) {
        $query = $this->db->select('DATE_FORMAT(soa.date_reported, "%b %Y") as month_year_reported, SUM(CASE WHEN cr.type = "hardware" THEN 1 ELSE 0 END) as no_of_reports_hardware, SUM(CASE WHEN cr.type = "software" THEN 1 ELSE 0 END) as no_of_reports_software', false)
                          ->from('service_order so')
                          ->join('service_order_acceptance soa', 'so.ref_no = soa.ref_no')
                          ->join('service_order_completion soc', 'so.ref_no = soc.ref_no')
                          ->join('computer_resources cr', 'cr.resource_id = so.complaint_resource_id', 'left')
                          ->where('soc.status', 'close')
                          ->group_by('DATE_FORMAT(soa.date_reported, "%M %Y")')
                          ->order_by('soa.date_reported asc')
                          ->limit(12, $offset)
                          ->get();

        return ($query->num_rows()) ? $query->result() : FALSE;
    }

    public function get_report_counts_classroom($year) {
        $query = $this->db->select('cd.designation, COUNT(cd.designation) AS no_of_reports', false)
                          ->from('service_order so')
                          ->join('service_order_acceptance soa', 'so.ref_no = soa.ref_no')
                          ->join('service_order_completion soc', 'so.ref_no = soc.ref_no')
                          ->join('computers c', 'c.computer_name = so.computer_name', 'left')
                          ->join('computer_designation cd', 'cd.computer_id = c.computer_id', 'inner')
                          ->join('rooms r', 'r.room_no = cd.designation', 'left')
                          ->join('classrooms cl', 'cl.room_id = r.room_id', 'inner')
                          ->group_by('cd.designation')
                          ->where('soc.status', 'close')
                          ->where('DATE_FORMAT(soa.date_reported, "%Y") =', $year )
                          ->get();

        return ($query->num_rows()) ? $query->result() : FALSE;
    }

    public function get_report_counts_cluster($year) {
        $query = $this->db->select('cd.designation, COUNT(cd.designation) AS no_of_reports', false)
                          ->from('service_order so')
                          ->join('service_order_acceptance soa', 'so.ref_no = soa.ref_no')
                          ->join('service_order_completion soc', 'so.ref_no = soc.ref_no')
                          ->join('computers c', 'c.computer_name = so.computer_name', 'left')
                          ->join('computer_designation cd', 'cd.computer_id = c.computer_id', 'inner')
                          ->join('clusters clr', 'clr.cluster_code = cd.designation', 'inner')
                          ->join('rooms r', 'r.room_id = clr.room_id', 'left')
                          ->group_by('cd.designation')
                          ->where('soc.status', 'close')
                          ->where('DATE_FORMAT(soa.date_reported, "%Y") =', $year )
                          ->get();

        return ($query->num_rows()) ? $query->result() : FALSE;
    }

    public function get_complaint_details($complaint_type) {
        $params = [];

        $sql = 'SELECT resource_id, resource_name ';
        $sql .= 'FROM computer_resources ';
        $sql .= 'WHERE type = ? ' ;
        $sql .= 'ORDER BY case when resource_id=1 then -1 else resource_name end';

        $params[] = $complaint_type;

        $query = $this->db->query($sql, $params);
        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    public function get_service_order_details_by_id($ref_no) {
        $query = $this->db->select('so.ref_no, so.emp_id, so.cluster_id, so.emp_name, so.computer_name, so.complaint_resource_id, cr.type complaint_type, cr.resource_name complaint, cr.resource_id complaint_resource_id, clr.cluster_name, so.position, so.contact_no, so.complaint_details, so.if_pulled_out')
                          ->select('soa.received_by, soa.assigned_to, soa.designate_to appoint, soa.designate_to completed_by, soa.date_reported, soa.time_reported')
                          ->select('CONCAT_WS(\' \', rb.firstname, rb.lastname) view_received_by, CONCAT_WS(\' \', cb.firstname, cb.lastname) view_completed_by, CONCAT_WS(\' \', at.firstname, at.lastname) view_assigned_to, CONCAT_WS(\' \', dt.firstname, dt.lastname) view_designate_to')
                          ->select('CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported')
                          ->select('soc.property_clerk, soc.property_date_received, soc.action_taken, soc.unit_status, CONCAT_WS(\' \', soc.date_finished, soc.time_finished) datetime_finished, soc.date_finished, soc.time_finished ')
                          ->from('service_order so')
                          ->join('service_order_acceptance soa', 'soa.ref_no = so.ref_no', 'inner')
                          ->join('service_order_completion soc', 'soc.ref_no = so.ref_no', 'inner')
                          ->join('computer_resources cr', 'cr.resource_id = so.complaint_resource_id', 'left')
                          ->join('clusters clr', 'clr.cluster_id = so.cluster_id', 'left')
                          ->join('users at', 'at.id = soa.assigned_to', 'left')
                          ->join('users dt', 'dt.id = soa.designate_to', 'left')
                          ->join('users rb', 'rb.id = soa.received_by', 'left')
                          ->join('users cb', 'cb.id = soc.completed_by', 'left')
                          ->where('so.ref_no', $ref_no)
                          ->get();

        return ( $query->num_rows() ) ? $query->row() : FALSE;
    }

    public function get_total_timeline(){
        $query = $this->db->get('service_order_timeline' );

        return $query->num_rows();
    }

    public function show_recently_encoded_data( $length ) {

        $query = $this->db->select('so.ref_no, so.cluster_id, so.computer_name, so.date_added, cr.resource_name complaint, so.complaint_details, sot.if_inverted, sot.color, clr.cluster_code, clr.cluster_name, soa.date_reported, soa.time_reported ')
                          ->from('service_order so')
                          ->join('service_order_acceptance soa', 'soa.ref_no = so.ref_no')
                          ->join('service_order_timeline sot', 'sot.ref_no = so.ref_no')
                          ->join('computer_resources cr', 'cr.resource_id = so.complaint_resource_id', 'left')
                          ->join('clusters clr', 'clr.cluster_id = so.cluster_id')
                          ->order_by('so.date_added desc')
                          ->limit($length, 0 )
                          ->get();
        return $query->result();
    }

    ######################## Classroom && Cluster Report Details ###########################
    public function get_report_details_records_total( $data, $room_no ) {
        extract($data);

        $sql = 'SELECT so.ref_no, c.computer_name, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'WHERE 1 AND cd.designation = ? AND soc.status = ?';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cr.type = ? ';
        }

        $params[] = $room_no;
        $params[] = 'close';

        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));

        if($type !== 'all'){
            $params[] = $type;
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_report_details_records_filtered($data, $room_no) {
        extract($data);
        $params = [];

        $sql = 'SELECT so.ref_no, c.computer_name, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'WHERE 1 AND cd.designation = ? AND soc.status = ?';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
        }

        $params[] = $room_no;
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
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }
    public function get_report_details($data, $room_no, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT so.ref_no, c.computer_name, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'WHERE 1 AND cd.designation = ? AND soc.status = ?';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
        }

        if(isset($order)){
            $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';
        }

        $sql .= 'LIMIT ?, ?';

        $params[] = $room_no;
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
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }


        $params[] = (int)$start;
        $params[] = (int)$length;


        $query = $this->db->query($sql, $params);

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    ######################## Resource Type Report Details ###########################
    public function get_resource_type_report_details_records_total( $data, $resource_id ) {
        extract($data);

        $sql = 'SELECT so.ref_no, c.computer_name, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'WHERE 1 AND so.complaint_resource_id  = ? AND soc.status = ?';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cd.designation_type = ? ';
        }

        $params[] = $resource_id;
        $params[] = 'close';

        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));

        if($type !== 'all'){
            $params[] = $type;
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_resource_type_report_details_records_filtered($data, $resource_id) {
        extract($data);
        $params = [];

        $sql = 'SELECT so.ref_no, c.computer_name, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'WHERE 1 AND so.complaint_resource_id = ? AND soc.status = ?';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cd.designation_type= ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
        }

        $params[] = $resource_id;
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
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }
    public function get_resource_type_report_details($data, $resource_id, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT so.ref_no, c.computer_name, cr.resource_name complaint, so.complaint_details, CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported ';
        $sql .= 'FROM service_order so ';
        $sql .= 'INNER JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'INNER JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'WHERE 1 AND so.complaint_resource_id = ? AND soc.status = ?';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type !== 'all'){
            $sql .= 'AND cd.designation_type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR cr.resource_name LIKE ? ';
            $sql .= 'OR so.complaint_details LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR soa.time_reported LIKE ?) ';
        }

        if(isset($order)){
            $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';
        }

        $sql .= 'LIMIT ?, ?';

        $params[] = $resource_id;
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
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }


        $params[] = (int)$start;
        $params[] = (int)$length;


        $query = $this->db->query($sql, $params);

        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }


    ############################### Service Orders Done For Reports #############################

    public function get_service_orders_done_records_total($type) {

        $sql = 'SELECT so.ref_no, so.emp_name, originator.cluster_code, so.computer_name, c.brand_clone_name,  CONCAT_WS(\': \', so.complaint_details) complaint_and_details, ';
        $sql .= 'CONCAT_WS(\' \', rb.firstname, rb.lastname) received_by, ';
        $sql .= 'CONCAT_WS(\' \', at.firstname, at.lastname) assigned_to, ';
        $sql .= 'CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported, ';
        $sql .= 'CONCAT_WS(\' \', soc.date_finished, soc.time_finished) datetime_finished, ';
        $sql .= 'soc.action_taken, soc.returned_to, soc.property_clerk, soc.property_date_received ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN clusters originator ON so.cluster_id = originator.cluster_id ';
        $sql .= 'LEFT JOIN users rb ON soa.received_by = rb.id ';
        $sql .= 'LEFT JOIN users at ON soa.assigned_to = at.id ';
        $sql .= 'LEFT JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON c.computer_id = cd.computer_id ';

        if($type === 'lecture' || $type == 'laboratory') {
            $sql .= 'LEFT JOIN rooms r ON cd.designation = r.room_no ';
            $sql .= 'LEFT JOIN classrooms cl ON r.room_id = cl.room_id ';
        }
        elseif($type === 'department' || $type == 'office') {
            $sql .= 'LEFT JOIN clusters clr ON cd.designation = clr.cluster_code ';
        }

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_service_orders_done_records_filtered($data, $type = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT so.ref_no, so.emp_name, originator.cluster_code, so.computer_name, c.brand_clone_name,  cr.type complaint_type, CONCAT_WS(\': \', cr.resource_name, so.complaint_details) complaint_and_details, ';
        $sql .= 'CONCAT_WS(\' \', rb.firstname, rb.lastname) received_by, ';
        $sql .= 'CONCAT_WS(\' \', at.firstname, at.lastname) assigned_to, ';
        $sql .= 'CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported, ';
        $sql .= 'CONCAT_WS(\' \', soc.date_finished, soc.time_finished) datetime_finished, ';
        $sql .= 'soc.action_taken, soc.returned_to, soc.property_clerk, soc.property_date_received ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN clusters originator ON so.cluster_id = originator.cluster_id ';
        $sql .= 'LEFT JOIN users rb ON soa.received_by = rb.id ';
        $sql .= 'LEFT JOIN users at ON soa.assigned_to = at.id ';
        $sql .= 'LEFT JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON c.computer_id = cd.computer_id ';

        if($type === 'lecture' || $type == 'laboratory') {
            $sql .= 'LEFT JOIN rooms r ON cd.designation = r.room_no ';
            $sql .= 'LEFT JOIN classrooms cl ON r.room_id = cl.room_id ';
        }
        elseif($type === 'department' || $type == 'office') {
            $sql .= 'LEFT JOIN clusters clr ON cd.designation = clr.cluster_code ';
        }

        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if($type === 'lecture' || $type == 'laboratory') {
            $sql .= 'AND cl.type = ? ';
        }
        elseif($type === 'department' || $type == 'office') {
            $sql .= 'AND clr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.emp_name LIKE ? ';
            $sql .= 'OR originator.cluster_code LIKE ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR c.brand_clone_name LIKE ? ';
            $sql .= 'OR cr.type LIKE ? ';
            $sql .= 'OR CONCAT_WS(\': \', cr.resource_name, so.complaint_details) LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', rb.firstname, rb.lastname) LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', at.firstname, at.lastname) LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soc.date_finished, soc.time_finished) LIKE ? ';
            $sql .= 'OR soc.action_taken LIKE ? ';
            $sql .= 'OR soc.returned_to LIKE ? ';
            $sql .= 'OR soc.property_clerk LIKE ? ';
            $sql .= 'OR soc.property_date_received LIKE ?)';
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
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
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

    public function get_service_orders_done($data, $type = false, $total_records, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT so.ref_no, so.emp_name, originator.cluster_code, so.computer_name, c.brand_clone_name,  cr.type complaint_type, CONCAT_WS(\': \', cr.resource_name, so.complaint_details) complaint_and_details, ';
        $sql .= 'CONCAT_WS(\' \', rb.firstname, rb.lastname) received_by, ';
        $sql .= 'CONCAT_WS(\' \', at.firstname, at.lastname) assigned_to, ';
        $sql .= 'CONCAT_WS(\' \', soa.date_reported, soa.time_reported) datetime_reported, ';
        $sql .= 'CONCAT_WS(\' \', soc.date_finished, soc.time_finished) datetime_finished, ';
        $sql .= 'soc.action_taken, soc.returned_to, soc.property_clerk, soc.property_date_received ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON so.ref_no = soa.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON so.ref_no = soc.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'LEFT JOIN clusters originator ON so.cluster_id = originator.cluster_id ';
        $sql .= 'LEFT JOIN users rb ON soa.received_by = rb.id ';
        $sql .= 'LEFT JOIN users at ON soa.assigned_to = at.id ';
        $sql .= 'LEFT JOIN computers c ON so.computer_name = c.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON c.computer_id = cd.computer_id ';

        if($type === 'lecture' || $type == 'laboratory') {
            $sql .= 'LEFT JOIN rooms r ON cd.designation = r.room_no ';
            $sql .= 'LEFT JOIN classrooms cl ON r.room_id = cl.room_id ';
        }
        elseif($type === 'department' || $type == 'office') {
            $sql .= 'LEFT JOIN clusters clr ON cd.designation = clr.cluster_code ';
        }

        $sql .= 'WHERE 1 AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';


        if($type === 'lecture' || $type == 'laboratory') {
            $sql .= 'AND cl.type = ? ';
        }
        elseif($type === 'department' || $type == 'office') {
            $sql .= 'AND clr.type = ? ';
        }

        if(!empty($search['value'])){
            $sql .= 'AND (so.ref_no = ? ';
            $sql .= 'OR so.emp_name LIKE ? ';
            $sql .= 'OR originator.cluster_code LIKE ? ';
            $sql .= 'OR so.computer_name LIKE ? ';
            $sql .= 'OR c.brand_clone_name LIKE ? ';
            $sql .= 'OR cr.type LIKE ? ';
            $sql .= 'OR CONCAT_WS(\': \', cr.resource_name, so.complaint_details) LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', rb.firstname, rb.lastname) LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', at.firstname, at.lastname) LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soa.date_reported, soa.time_reported) LIKE ? ';
            $sql .= 'OR CONCAT_WS(\' \', soc.date_finished, soc.time_finished) LIKE ? ';
            $sql .= 'OR soc.action_taken LIKE ? ';
            $sql .= 'OR soc.returned_to LIKE ? ';
            $sql .= 'OR soc.property_clerk LIKE ? ';
            $sql .= 'OR soc.property_date_received LIKE ?)';
        }

        if(isset($order)){
            $sql .= 'ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']) . ' ';//$order[0]['column']
        }
        $sql .= 'LIMIT ?, ?';

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
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
            $params[] = '%' . $search['value'] . '%';
        }

        $params[] = (int)$start;

        if($length == '-1') {
            $params[] = (int)$total_records;
        }
        else {
            $params[] = (int)$length;
        }
        $query = $this->db->query($sql, $params);
        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }

    ############################### Hardware Reports #############################

    public function get_software_hardware_reports_total() {

        $sql = 'SELECT DISTINCT cr.resource_name, cr.resource_id, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "lecture" THEN 1 ELSE 0 END) as lecture_reports, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "laboratory" THEN 1 ELSE 0 END) as laboratory_reports, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "department" THEN 1 ELSE 0 END) as department_reports, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "office" THEN 1 ELSE 0 END) as office_reports, ';
        $sql .= 'COUNT(cr.resource_name) as total ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN computers c ON c.computer_name = so.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'UNION ';
        $sql .= 'SELECT DISTINCT "OVERALL TOTAL" resource_name, resource_id, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "lecture" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "laboratory" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "department" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "office" THEN 1 ELSE 0 END), ';
        $sql .= 'COUNT(cr.resource_name) ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN computers c ON c.computer_name = so.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_software_hardware_reports_filtered($data, $resource_type, $type = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT cr.resource_name, cr.resource_id, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "lecture" THEN 1 ELSE 0 END) as lecture_reports, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "laboratory" THEN 1 ELSE 0 END) as laboratory_reports, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "department" THEN 1 ELSE 0 END) as department_reports, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "office" THEN 1 ELSE 0 END) as office_reports, ';
        $sql .= 'COUNT(cr.resource_name) AS total ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN computers c ON c.computer_name = so.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND cr.type = ? AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if(!empty($search['value'])){
            $sql .= 'AND cr.resource_name LIKE ? ';
        }

        $params[] = $resource_type;
        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));

        $sql .= 'GROUP BY (cr.resource_name) ';

        $sql .= 'UNION ';
        $sql .= 'SELECT DISTINCT "OVERALL TOTAL" resource_name, resource_id, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "lecture" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "laboratory" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "department" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "office" THEN 1 ELSE 0 END), ';
        $sql .= 'COUNT(cr.resource_name) ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN computers c ON c.computer_name = so.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND cr.type = ? AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if(!empty($search['value'])){
            $params[] = '%' . $search['value'] . '%';
        }

        $params[] = $resource_type;
        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));

        $query = $this->db->query($sql, $params);

        return $query->num_rows();
    }

    public function get_software_hardware_reports($data, $resource_type, $details = false) {
        extract($data);
        $params = [];

        $sql = 'SELECT DISTINCT cr.resource_name, cr.resource_id, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "lecture" THEN 1 ELSE 0 END) as lecture_reports, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "laboratory" THEN 1 ELSE 0 END) as laboratory_reports, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "department" THEN 1 ELSE 0 END) as department_reports, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "office" THEN 1 ELSE 0 END) as office_reports, ';
        $sql .= 'COUNT(cr.resource_name) AS total ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN computers c ON c.computer_name = so.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_completion soc ON soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND cr.type = ? AND soc.status = ? ';
        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if(!empty($search['value'])){
            $sql .= 'AND cr.resource_name LIKE ? ';
        }

        $params[] = $resource_type;
        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));

        $sql .= 'GROUP BY (cr.resource_name) ';

        $sql .= 'UNION ';
        $sql .= 'SELECT DISTINCT "OVERALL TOTAL" resource_name, resource_id, ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "lecture" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "laboratory" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "department" THEN 1 ELSE 0 END), ';
        $sql .= 'SUM(CASE WHEN cd.designation_type = "office" THEN 1 ELSE 0 END), ';
        $sql .= 'COUNT(cr.resource_name) ';
        $sql .= 'FROM service_order so ';
        $sql .= 'LEFT JOIN computers c ON c.computer_name = so.computer_name ';
        $sql .= 'LEFT JOIN computer_designation cd ON cd.computer_id = c.computer_id ';
        $sql .= 'LEFT JOIN service_order_completion soc ON soc.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN service_order_acceptance soa ON soa.ref_no = so.ref_no ';
        $sql .= 'LEFT JOIN computer_resources cr ON cr.resource_id = so.complaint_resource_id ';
        $sql .= 'WHERE 1 AND cr.type = ? AND soc.status = ? ';

        $sql .= 'AND (soa.date_reported >= ? AND soa.date_reported <= ?) ';

        if(!empty($search['value'])){
            $params[] = '%' . $search['value'] . '%';
        }

        $params[] = $resource_type;
        $params[] = 'close';
        $params[] = date('Y-m-d', strtotime($date_from));
        $params[] = date('Y-m-d', strtotime($date_to));

        $query = $this->db->query($sql, $params);
        return ( $query->num_rows() ) ? $query->result() : FALSE;
    }
}
