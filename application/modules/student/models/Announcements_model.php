<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Announcements_model extends CI_Model {
 	
	
	public function get_announcements ($coaching_id=0, $status=0) {
		$now = time ();
		//$this->db->where ('start_date >=', $now);
		//$this->db->where ('end_date <=', $now);
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('status', 1);
		$this->db->order_by ('end_date', 'DESC');
		return $this->db->get ("coaching_announcements")->result_array();		
	}

	public function get_announcement ($coaching_id=0, $annc_id=0) {
		$now = time ();
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('status', 1);
		$this->db->where ('announcement_id', $annc_id);
		return $this->db->get ("coaching_announcements")->row_array();		
	}
	
}	