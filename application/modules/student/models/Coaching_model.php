<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coaching_model extends CI_Model {
	
	public function get_coaching ($coaching_id=0) {
		$this->db->where ('coachings.id', $coaching_id);
		$this->db->from ('coachings');
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else { 
			return false;
		}
	}

	public function get_coaching_subscription ($coaching_id=0) {
		$this->db->select ('C.*, CS.starting_from, CS.ending_on, CS.created_by, SP.*, SP.id AS sp_id');
		$this->db->from ('coachings C, coaching_subscriptions CS, subscription_plans SP');
		$this->db->where('CS.coaching_id=C.id');
		$this->db->where('SP.id=CS.plan_id');
		$this->db->where ('CS.id', $coaching_id);
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else { 
			return false;
		}
	}

	public function get_coaching_by_slug ($coaching_slug='') {
		$this->db->where ('coaching_url', $coaching_slug);
		$this->db->from ('coachings');
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else {
			return false;
		}
	}

	public function get_coaching_by_uid ($coaching_uid=0) {
		$this->db->where ('reg_no', $coaching_uid);
		$sql = $this->db->get ('coachings');
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else { 
			return false;
		}
	}

	public function find_coaching () {
		$result = [];
		$search = $this->input->post ('search');
		if (! empty ($search)) {
			$this->db->select ('id, coaching_name, coaching_url');
			$this->db->like ('coaching_name', $search, 'both');
			$sql = $this->db->get ('coachings');
			//echo $this->db->last_query ();
			if (! empty ($sql->result_array ()))  {
				foreach ($sql->result_array () as $row) {
					$coaching_id = $row['id'];
					$upload_dir = $this->config->item ('sys_dir') . 'coachings/' .$coaching_id .'/';
					$file_name = $this->config->item ('coaching_logo');
					$file_path = base_url($upload_dir . $file_name);
					if (is_file ($file_path)) {
						$logo = $file_path;
					} else {
						$logo = '';
					}
					$row['logo'] = $logo;
					$result[] = $row;
				}
			}			
		}
		return $result;
	}
	
}