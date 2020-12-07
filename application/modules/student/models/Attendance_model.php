<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_model extends CI_Model { 	
	
	public function my_attendance ($coaching_id=0, $member_id=0, $from=0, $to=0) { 

		if ($from == 0) {
			$from = time ();
		}
		if ($to == 0) {
			$to = time ();
		}
		
		$this->db->where ('coaching_id', $coaching_id );
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
}