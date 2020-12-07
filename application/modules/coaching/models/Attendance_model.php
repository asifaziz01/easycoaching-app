<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_model extends CI_Model { 
	

	public function search_users ($coaching_id=0) {
		
		$role_id = $this->input->post ('search-role');
		$status = $this->input->post ('search-status');
		$batch_id = $this->input->post ('search-batch');		
		$search = $this->input->post ('search_text');
		
		$this->db->where ('adm_no', $search);
		$this->db->or_where ('login', $search);
		$this->db->or_where ('first_name', $search);
		$this->db->or_where ('second_name', $search);
		$this->db->or_where ('last_name', $search);
		$this->db->or_where ('email', $search);
		if ($role_id > 0) {
			$this->db->or_where ('role_id', $role_id);
		}
		if ($status > '-1') {
			$this->db->or_where ('status', $status);
		}
		if ($batch_id > 0) {
			$this->db->or_where ('batch_id', $batch_id);
		}
		$this->db->where ('coaching_id', $coaching_id );
		$sql = $this->db->get ('members');
		
		return $sql->result_array ();
	}

	
	public function mark_attendance ($coaching_id=0, $member_id=0, $status=0, $date='') {
		
		$taken_by = intval ($this->session->userdata ('member_id'));
		
		$data = array ('member_id'=>$member_id, 'attendance'=>$status, 'date'=>$date, 'remark'=>'', 'taken_by'=>$taken_by, 'creation_date'=>time ());
		
		$search = array ('member_id'=>$member_id, 'date'=>$date);
		
		$this->db->where ($search);
		$sql = $this->db->get ('coaching_attendance');
		if ($sql->num_rows() > 0 ) {
			$this->db->set ('attendance', $status);
			$this->db->where ('member_id', $member_id);
			$this->db->where ('date', $date);
			$this->db->update ("coaching_attendance");
		} else {
			$data['coaching_id'] = $coaching_id;
			$this->db->insert ('coaching_attendance', $data);
		}
	}
	
	public function member_attendance ($member_id=0, $date=0) {	

		$this->db->where (array("member_id"=>$member_id, "date"=>$date));
		$query = $this->db->get("coaching_attendance");
		$row = $query->row_array();
		return $row;
	}

	public function save_remark ($member_id=0, $date=0) {

		$remark = ascii_to_entities ($this->input->post ('remark'));

		list ($d, $m, $y) = explode ('-', $date);
		$date = mktime (0, 0, 0, $m, $d, $y);

		$this->db->where (array("member_id"=>$member_id, "date"=>$date));
		$query = $this->db->get("coaching_attendance");
		if ($query->num_rows() > 0) {
			$this->db->where ('member_id', $member_id);
			$this->db->where ('date', $date);
			$this->db->update ("coaching_attendance", array ('remark'=>$remark) );
		} else {
			$data = array ('member_id'=>$member_id, 'remark'=>$remark, 'date'=>$date);
			$data['creation_date'] = time ();
			$this->db->insert ("coaching_attendance", $data);
		}
	}
	
	
	public function member_report ($member_id, $from, $to) { 

		$this->db->where ('member_id', $member_id );
		$this->db->where ('date BETWEEN ' . $from . ' AND ' . $to);
		$sql = $this->db->get ('coaching_attendance');
		if ($sql->num_rows () > 0 ) {
			$result = $sql->result_array ();
			return $result;
		} else {
			return false;
		}
	}


	public function get_coaching_attendance ($from, $to) { 

		$this->db->where ('date BETWEEN ' . $from . ' AND ' . $to);
		$sql = $this->db->get ('coaching_attendance');
		if ($sql->num_rows () > 0 ) {
			$result = $sql->result_array ();
			return $result;
		} else {
			return false;
		}
	}
	
	
}