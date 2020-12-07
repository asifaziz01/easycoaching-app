<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coaching_model extends CI_Model {
    
	// Save account
	public function coaching_exists ($contact='', $name='') {
		$this->db->select ('M.member_id');
		$this->db->from ('members M');
		$this->db->join ('coachings C', 'M.coaching_id=C.id');
		$this->db->where ('M.primary_contact', $contact);
		$this->db->where ('M.role_id', USER_ROLE_COACHING_ADMIN);
		$this->db->where ('C.coaching_name', $name);
		$sql = $this->db->get ();
		if ($sql->num_rows () > 0) {
			return true;
		} else {
			return false;			
		}
	}


	public function create_coaching () {
		$coaching = [];
		
		$name = explode (' ', $this->input->post ('first_name'), 2);
		$first_name = $name[0];
		if (isset ($name[1])) {
			$last_name  = $name[1];
		} else {
			$last_name = '';
		}

		if ($this->input->post ('coaching_url')) {
			$coaching_url = str_replace ('-', '_', $this->input->post ('coaching_url'));
		} else {
			$coaching_url = '';
		}

		if ($this->input->post ('city')) {
			$city = $this->input->post ('city');
		} else {
			$city = '';
		}

		if ($this->input->post ('website')) {
			$website = prep_url($this->input->post ('website'));
		} else {
			$website = '';
		}

		$coaching['subscription_type'] = FREE_SUBSCRIPTION_PLAN_ID;
		$coaching['subscription_status'] = SUBSCRIPTION_STATUS_ACTIVE;

		$doj = time ();
		$doe = time () + ( FREE_SUBSCRIPTION_PERIOD * 24 * 3600);			// 30 days subscription plan
		$coaching = [
				'coaching_name'=>	$this->input->post ('coaching_name'), 
				'coaching_url' => 	$coaching_url,
	    		'email'=>			$this->input->post ('email'),
				'city'=>			$city, 
				'website'=> 		$website,
				'doj'=> 			$doj,
				'doe'=>				'',
				'status'=>			COACHING_ACCOUNT_ACTIVE,
				'creation_date'=>	time (),
				'created_by'=>		0
			];
		
		$this->db->insert ('coachings', $coaching);
		$coaching_id = $this->db->insert_id ();

		// Add free subscription plan
		$plan['plan_id']	= FREE_SUBSCRIPTION_PLAN_ID;
		$plan['coaching_id']= $coaching_id;
		$plan['starting_from']= $doj;
		$plan['ending_on']	= $doe;
		$plan['status']	= 1;
		$plan['creation_date']	= time ();
		$plan['created_by'] = 0;
		$this->db->insert ('coaching_subscriptions', $plan);
			
		// Set Reference-id
		$access_code = $this->common_model->generate_coaching_id ($coaching_id);
		$this->db->set ('reg_no', $access_code);
		$this->db->set ('coaching_url', $access_code);
		$this->db->where ('id', $coaching_id);
		$this->db->update ('coachings');

		// Create admin account

		$user = [];		
		$user['login'] 		= '';
		$user['user_token'] = '';
		$password 			= $this->input->post ('password');
		$user['password'] 	= password_hash($password, PASSWORD_DEFAULT);
		$user['first_name'] = $first_name;
		$user['last_name']  = $last_name;
		$user['role_id'] 	= USER_ROLE_COACHING_ADMIN;
		$user['coaching_id']= $coaching_id;
		$user['email'] 		= $this->input->post ('email');
		$user['primary_contact'] 	= $this->input->post ('primary_contact');
		$user['status']  		= USER_STATUS_ENABLED;
		$user['created_by']  	= 0;
		$user['creation_date'] 	= time ();
		$this->create_user ($user, $access_code);

		$coaching_url = site_url ('login/user/index/?sub='.$access_code);
		$return = [
			'name'=>$user['first_name'],
			'coaching_name'=>$this->input->post('coaching_name'),
			'access_code'=>$access_code,
			'url'=>$coaching_url,
			'login'=>$this->input->post ('primary_contact'),
			'password'=>$password,
			'email'=>$this->input->post ('email'),
			];

		return $return;
	}

	// Create coaching user
	public function create_user ($data, $slug='') {
	
		$sql = $this->db->insert('members', $data);
		$member_id = $this->db->insert_id ();
		
		// Generate user id
		$user_id = $this->users_model->generate_reference_id ($member_id);

		// User Token
		$salt = random_string ('alnum', 4);
		$str = $user_id . $data['coaching_id'] . $member_id . $salt;
		$user_token = md5($str);

		$this->db->set ('adm_no', $user_id);
		$this->db->set ('login', $user_id);
		$this->db->set ('user_token', $user_token);
		$this->db->where ('member_id', $member_id);
		$this->db->update ('members');

		return $member_id;
	}

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
		$this->db->where ('C.id', $coaching_id);
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
		$this->db->or_where ('reg_no', $coaching_slug);
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

	public function get_coaching_tests ($coaching_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('coaching_tests');
		$result = $sql->result_array ();
		return $result;
	}

	public function get_coaching_users ($coaching_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('members');
		$result = $sql->result_array ();
		return $result;
	}

	public function get_coaching_announcements ($coaching_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->order_by ('start_date', 'ASC');
		$sql = $this->db->get ('coaching_announcements');
		$result = $sql->result_array ();
		return $result;
	}

	public function coaching_plans ($coaching_id=0, $plan_id=0, $status='') {
		$this->db->select ('CP.*, TP.*');
		$this->db->from ('coaching_test_plans AS CP');
		$this->db->where ('CP.coaching_id', $coaching_id);
		if ($plan_id > 0) {
			$this->db->where ('CP.plan_id', $plan_id);
		}
		if ($status != '') {
			$this->db->where ('CP.status', $status);
		}
		$this->db->join ('test_plans AS TP', 'TP.plan_id = CP.plan_id');
		$sql = $this->db->get ();
		if ($plan_id > 0) {
			$result = $sql->row_array ();
		} else {
			$result = $sql->result_array ();
		}
		return $result;
		
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
	
	public function num_users ($coaching_id=0, $role_id=0, $status='-1') {
		if ($role_id > 0) {
			$this->db->where ('role_id', $role_id);
		}
		if ($status > '-1') {
			$this->db->where ('status', $status);
		}
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('members');
		return $sql->num_rows ();
	}

	public function num_tests ($coaching_id=0, $type=0, $status='-1') {
		if ($type > 0) {
			$this->db->where ('test_type', $type);
		}
		if ($status > '-1') {
			$this->db->where ('finalized', $status);
		}
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('coaching_tests');
		return $sql->num_rows ();
	}
}