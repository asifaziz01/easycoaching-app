<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {
    
	
	public function get_user ($member_id=0) {		
		$this->db->where ('member_id', $member_id);
		$sql = $this->db->get ('members');
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();			
			return $result;
		} else {
			return false;
		}
	}
	
	public function member_profile ($member_id=0) {
		$result = array ();
		$this->db->where ('member_id', $member_id);
		$sql = $this->db->get ('coaching_user_profile');
		if  ($sql->num_rows () > 0 ) { 
			$result = $sql->row_array ();
		} else {
			foreach ($sql->list_fields() as $field) {
			   $result[$field] = '';
			} 
		}
		return $result;
	}	

	// Save account
	public function save_account ($coaching_id=0, $member_id=0, $status=USER_STATUS_ENABLED) {
	    
	    if ($this->input->post ('dob')) {
			$dob = $this->input->post ('dob');
			list ($y, $m, $d) = explode ('-', $dob);
			$date = $m.'/'.$d.'/'.$y;
		} else {
			$date = '';
		}
	    
		$data = array (
			'role_id'	=>		$this->input->post ('user_role'),
			'sr_no'		=>		$this->input->post ('sr_no'), 
			'first_name'=>		$this->input->post ('first_name'), 
			'second_name'=>		$this->input->post ('second_name'), 
			'last_name'	=>		$this->input->post ('last_name'), 
			'primary_contact'=> $this->input->post ('primary_contact'),
			'email'		=>		$this->input->post ('email'),
			'dob'		=>		$date,
			'gender'	=>		$this->input->post ('gender'),
		);
		if ($member_id > 0) { 
			// update account
			$this->db->where ('member_id', $member_id);
			$this->db->update ('members', $data);			
		} else {
			// create profile
			$password = $this->input->post ('password');			

			$password_hash = password_hash($password, PASSWORD_DEFAULT);
			$data['coaching_id']  = $coaching_id;
			$data['password']	= $password_hash;
			$data['status']  = $status;
			$data['gender'] = 'n';
			$data['link_send_time']	= time();
			$data['creation_date'] = time ();
			$data['created_by'] = $this->session->userdata ('member_id');
			$sql = $this->db->insert ('members', $data);
			$member_id = $this->db->insert_id ();

			// Set Userid
			$user_id = $this->generate_reference_id ($member_id);
			$user_token = md5 ($user_id);
			$this->db->set ('user_token', $user_token);
			$this->db->set ('login', $user_id);
			$this->db->set ('adm_no', $user_id);
			$this->db->where ('member_id', $member_id);
			$this->db->update ('members');


		}
		//$this->save_member_batch ($member_id);
		//$this->save_class ($member_id, $coaching_id);
		return $member_id;
	}
	
	
	/* VIEW PROFILE IMAGE
	*/
	public function view_profile_image ($member_id=0, $coaching_id=0) { 
	
		$this->load->helper('file');
		$path_to_image_dir = $this->config->item('profile_picture_path');
		$file_name = 'pi_'.$member_id.'.gif';
		$file_path = $path_to_image_dir . $file_name;
		
		// if a profile_image of this user does not exists we can proceed normally
		if ( read_file ( $file_path ) == false ) {
			$profile_image = $path_to_image_dir . 'default.png';
		} else {
			$profile_image = $path_to_image_dir . $file_name . '?' . time ();
		}
		return $profile_image;	
	}
	
	public function upload_profile_picture ($member_id, $coaching_id=0) {
		
		$this->load->helper('directory');
		$this->load->helper('file');
		
		// Set upload path
		$upload_dir =  $this->config->item ('profile_picture_path');

		$file_name = 'pi_'.$member_id.'.gif';
		$file_path = $upload_dir . $file_name;		
		
		if ( ! is_dir ($upload_dir) ) {
			mkdir ($upload_dir, 0777, true);
		}
		
		// upload parameters
		$options['upload_path'] 	= $upload_dir;
		$options['allowed_types'] 	= 'gif|jpg|png';
		//$options['max_size']		= MAX_FILE_SIZE;
		$options['file_name']		= $file_name;
		$options['overwrite']		= true;
		
		// load upload library
		$this->load->library ('upload', $options); 		
		
		if ( ! $this->upload->do_upload () ) {
			$response = $this->upload->display_errors ();
		} else {   
			$upload_data = $this->upload->data();
			$config['image_library'] = 'gd2';
			$config['source_image']	= $file_path ;
			$config['create_thumb'] = false; 
			$config['maintain_ratio'] = false;
			$config['width']	 	= 240;
			$config['height']		= 240;
			//$config['master_dim']	= 'auto';
			
			$this->load->library('image_lib', $config);
		
			$this->image_lib->resize();
			$response = $upload_data;
		}
		
		return $response;;
	}
	
	/* REMOVE PROFILE IMAGE	*/
	public function remove_profile_image ($member_id) {
		$this->load->helper('file');
		$path_to_image_dir =  $this->config->item ('profile_picture_path');
		$file_name = 'pi_'.$member_id.'.gif';
		$file_path = $path_to_image_dir . $file_name;
		
		if ( file_exists($file_path)){
			delete_files ( './' . $file_path );
			unlink(($file_path));
			return true;
		} else{
			return false;
		}
	}
	
	
	public function get_user_by_login ($login_id=0) {
		$this->db->where ('login_id', $login_id);
		$sql = $this->db->get ('users');
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else {
			return false;
		}		
	}	
	
	public function get_user_roles ($admin_user=0, $level=0) {
		if ($admin_user > 0) {
			$this->db->where ('admin_user', $admin_user);			
		}
		if ($level > 0) {
			$this->db->where ('role_lvl >=', $level);			
		}
		$this->db->where ('status', 1);
		$this->db->order_by ('role_lvl', 'ASC');
		$sql = $this->db->get ('sys_roles');
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->result_array ();
			return $result;
		} else { 
			return false;
		}		
	}

	
	public function user_roles_by_level ($level=0) {
		if ($level > 0) {
			$this->db->where ('role_lvl >=', $level);			
		}
		$this->db->where ('status', 1);
		$this->db->order_by ('role_lvl', 'ASC');
		$sql = $this->db->get ('sys_roles');
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->result_array ();
			return $result;
		} else { 
			return false;
		}		
	}

	public function user_role_name ($role_id=0) {		
		$this->db->where ('role_id', $role_id);
		$sql = $this->db->get ('sys_roles');
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else { 
			return false;
		}		
	}
	
	/*---=== MEMBER LOG FUNCTIONS ===---*/
	public function add_member_log ($log_id=0, $member_id=0) {
		$comment = $this->input->post("action_log");
		$values = array (
			"comment"	=>ascii_to_entities($comment),
			"member_id"	=>$member_id,
			"log_by"	=>$this->session->userdata("member_id")
		);
		if ($log_id > 0) {
			$this->db->where("id", $log_id);
			$this->db->update("member_log", $values);			
		} else {
			$this->db->insert("member_log", $values);
		}
		
		return true;
	}

	
	public function last_log ($member_id=false){
		$this->db->where(array("member_id"=>$member_id));
		$this->db->order_by("date", "DESC");
		$query = $this->db->get("member_log");
		if($query->num_rows()>0){
			$row = $query->row_array();
			return $row;
		}else{
			return false;
		}
	}
	 
	public function all_log ($member_id=false){
		$this->db->where(array("member_id"=>$member_id));
		$this->db->order_by("date", "DESC");
		$query = $this->db->get("member_log");
		if($query->num_rows()>0){
			$results = $query->result_array();
			return $results;
		}else{
			return false;
		}
	}
	
	public function get_action_log ($log_id=false){
		$this->db->where("id", $log_id);
		$query = $this->db->get("member_log");
		if ($query->num_rows()>0) {
			$row = $query->row_array();
			return $row; 
		} else {
			return false;
		}
	}
	
	/*---=== ===---*/
	public function delete_account ($member_id=0) {
		$this->db->where ("member_id", $member_id);
		$this->db->delete ("members");
		
		// Clear member log
		return true;
	}

	/*---=== ===---*/
	public function delete_member_log ($log_id=false){
		$this->db->where("id", $log_id);
		$this->db->delete("member_log");
		return true;
	}
	
	// Change account password
	public function update_password ($member_id, $password) {
		$password = password_hash($password, PASSWORD_DEFAULT);
		$data = array ('password'=> $password);
		$this->db->where ('member_id', $member_id);
		$query = $this->db->update ("members", $data );
	}

	/*---=== ===---*/	
	// Enable User
	public function enable_user ($member_id) {
		$data['status'] = 1;
		$this->db->where ("member_id", $member_id);
		$query = $this->db->update ('members', $data);
		if ($query)	{
			return true;
		} else {
			return false;
		}
	}

	// Disable User
	public function disable_user ($member_id) {
		$data['status'] = 0;
		$this->db->where ("member_id", $member_id);
		$query = $this->db->update ('members', $data);		
		if ($query)	{
			return true;
		} else {
			return false;
		}
	}	
	
	/*---=== ===---*/
	public function check_admno ($adm_no, $member_id) {
		$this->db->where ('adm_no', $adm_no);
		$this->db->where ('member_id <>', $member_id);
		$sql = $this->db->get ('members');
		if ($sql->num_rows () > 0 ) {
			return true;
		} else {
			return false;
		}
	}


	// tests taken within range
	public function user_registration_between ($start_date=0) {
		
		$result = array ();		
		
		// current timestamp
		$today = time ();
		
		// start date
		if ($start_date == 0) {
			$end_date = time ();
			// 7 days tests from today
			for ($i=0; $i<7; $i++) {
				$count = 0;
				$today_midnight = mktime (0, 0, 0, date ('m'), date ('d')-$i, date ('Y')); 				
				$start_date = $today_midnight;
				
				$this->db->where ('creation_date >= ', $start_date );
				$this->db->where ('creation_date < ',  $end_date);
				//$this->db->group_by ('test_id');
				$sql = $this->db->get ('members');
				if ($sql->num_rows () > 0) {
					$count = $sql->num_rows ();					
				}
				$result[$end_date] = $count;
				$end_date = $start_date - 1;			// minus one second
			}
			$result = array_reverse ($result, true);
			return $result;
		} else {
			$end_date = $today;
			$this->db->where ('creation_date >= ', $start_date );
			$this->db->where ('creation_date <= ',  $end_date);
			//$this->db->group_by ('test_id');
			$sql = $this->db->get ('members');
			$count = $sql->num_rows ();
			$result[$end_date] = $count;
		}
		
	}
	
	/* Reporting Functions */
	public function count_all_users ($coaching_id=0) {
		if ($coaching_id > 0) {
			$this->db->where ('coaching_id', $coaching_id);
		}
		return $this->db->count_all ('members');
	}
	
	public function count_users ($status=USER_STATUS_ALL, $role_id=0, $coaching_id=0) {
		
		if ($status <> USER_STATUS_ALL) {
			$this->db->where ('status', $status);
		} 
		
		if ($role_id > 0) {
			$this->db->where ('role_id', $role_id);			
		}
		if ($coaching_id > 0) {
			$this->db->where ('coaching_id', $coaching_id);			
		}
		$sql = $this->db->get ('members');		
		return $sql->num_rows ();
	}
	
	// Get Batches
	public function get_batches ($coaching_id=0) {
	    $this->db->where ('coaching_id', $coaching_id);
	    $sql = $this->db->get ('coaching_batches');
	    return $sql->result_array ();
	}
	
	// Get Batches
	public function get_batch_details ($batch_id=0) {
	    $this->db->where ('batch_id', $batch_id);
	    $sql = $this->db->get ('coaching_batches');
	    return $sql->row_array ();
	}
	
	// Save member batch
	public function save_member_batch ($member_id=0) { 
		$batch_id = $this->input->post ('batch');
		$profile = array (
						'member_id'=>$member_id,
						'batch_id'=>$batch_id,
					);
					
		$this->db->where ('member_id', $member_id);
		$this->db->where ('batch_id', $batch_id);
		$sql = $this->db->get ('coaching_batch_users');
		if ($sql->num_rows () == 0) {
			// create profile
			if ($batch_id > 0) {
				$this->db->insert ('coaching_batch_users', $profile);				
			}
		}
	}
	
	public function member_batches ($member_id = 0) {
		$this->db->where('member_id', $member_id);
		$sql = $this->db->get ('coaching_batch_users');		
		$result = $sql->result_array ();		
		return $result;
	}	
	
	public function get_member_batches ($batch_id=0, $batch_type=0) {
		
		$time = time ();
		$result = false;
		if ($batch_type > 0) {			
			if ($batch_type == UPCOMING_BATCH) {
				$this->db->where ('start_date >', $time);
				$this->db->where ('end_date >', $time);
			} else if ($batch_type == ONGOING_BATCH) {
				$this->db->where ('start_date <=', $time);
				$this->db->where ('end_date >=', $time);
			} else {
				$this->db->where ('start_date <', $time);
				$this->db->where ('end_date <', $time);			
			}
		}
		
		if ($batch_id > 0) {
			$this->db->where ('batch_id', $batch_id);
		}
		
		$sql = $this->db->get ('coaching_batches');
		
		if ($batch_id > 0) {
			return $sql->row_array ();
		} else {
			return $sql->result_array ();				
		}
	
	}
	
	public function create_batch ($coaching_id=0, $batch_id=0) {
		
		$time = time ();
		$data = array ();
		$data['batch_code'] = $this->input->post ('batch_code');
		$data['batch_name'] = $this->input->post ('batch_name');
		$start_date = $this->input->post ('start_date');
		$end_date 	= $this->input->post ('end_date');
		if (! empty($start_date)) {
			list ($sy, $sm, $sd)	= explode ('-', $start_date);
			$sdate = mktime (0, 0, 0, $sm, $sd, $sy);			
			$data['start_date'] = ($sdate);
		}
		if (! empty($end_date)) {
			list ($ey, $em, $ed)	= explode ('-', $end_date);
			$edate = mktime (0, 0, 0, $em, $ed, $ey);
			$data['end_date'] = ($edate);
		}
		
		if ($batch_id > 0) {
			$this->db->where ('batch_id', $batch_id);
			$this->db->update ('coaching_batches', $data);			
		} else {
			$data['status'] = 1;
			$data['coaching_id'] = $coaching_id;
			$data['creation_date'] = $time;
			$data['created_by'] = intval ($this->session->userdata ('member_id'));
			$this->db->insert ('coaching_batches', $data);
		}
	}

	public function batch_users ($batch_id=0) {
		$this->db->select ('M.*');
		$this->db->where ('MB.batch_id', $batch_id);
		$this->db->from ('coaching_batch_users MB');
		$this->db->join ('members M', 'MB.member_id=M.member_id');
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->result_array ();
		}else{
			$result = false;
		}		
		return $result;
	}	

	public function users_not_in_batch ($batch_id=0, $coaching_id=0) {
		// Get batch users
		$this->db->where ('batch_id', $batch_id);
		$sql = $this->db->get ('coaching_batch_users');
		$users = $sql->result_array ();
		$data = [];
		if (! empty ($users)) {
			foreach ($users as $u) {
				$data[] = $u['member_id'];
			}
		}
		// Get all users not in batch
		$this->db->select ('M.*, R.description');
		$this->db->from ('members M');
		$this->db->join ('sys_roles R', 'M.role_id=R.role_id');
		if (! empty($data)) {
			$this->db->where_not_in ('M.member_id', $data);
		}
		$this->db->where ('M.coaching_id', $coaching_id);
		$sql = $this->db->get ();
		$users = $sql->result_array ();
		return $users;
	}	
	
	public function save_batch_users ($batch_id=0) {
		
		$users = $this->input->post ('users');
		foreach ($users as $member_id) {
			$this->db->where ('batch_id', $batch_id);
			$this->db->where ('member_id', $member_id);
			$sql = $this->db->get ('coaching_batch_users');
			if  ($sql->num_rows () == 0 ) { 
				$data['member_id'] = $member_id;
				$data['batch_id']  = $batch_id;
				$sql = $this->db->insert ('coaching_batch_users', $data);
				//echo $this->db->last_query ();
			}
		}
	}	

	public function remove_batch_user ($batch_id=0, $member_id=0) {		
		$this->db->where ('batch_id', $batch_id);
		$this->db->where ('member_id', $member_id);
		$sql = $this->db->delete ('coaching_batch_users');
	}

	public function delete_batch ($batch_id=0) {
		$this->db->where ('batch_id', $batch_id);
		$sql = $this->db->delete ('coaching_batches');

		$this->db->where ('batch_id', $batch_id);
		$sql = $this->db->delete ('coaching_batch_users');
	}
	
	/*
	EXPORT USERS
	*/
	public function upload_users_csv ($coaching_id=0, $role_id=USER_ROLE_STUDENT, $data) {

		$data['coaching_id']  	= $coaching_id;
		$data['link_send_time']	= time();
		$data['role_id']  		= $role_id;
		$data['status']  		= USER_STATUS_ENABLED;
		$data['creation_date'] 	= time ();
		$data['created_by'] 	= intval($this->session->userdata ('member_id'));
		
		$sql = $this->db->insert ('members', $data);
		$member_id = $this->db->insert_id ();

		// Set Userid
		$user_id = $this->generate_reference_id ($member_id);
		$this->db->set ('login', $user_id);
		$this->db->set ('adm_no', $user_id);
		$this->db->where ('member_id', $member_id);
		$this->db->update ('members');
	}

	public function  import_cleanup () {
		$this->db->where ('login', '');
		$this->db->where ('adm_no', '');
		$this->db->or_where ('login', NULL);
		$this->db->or_where ('adm_no', NULL);
		$this->db->delete ('members');
	}
	
	public function export_to_csv ($coaching_id=0, $role_id=USER_ROLE_STUDENT, $members=array()) {
 
		$response = array(); 
		// Select record
		$select = array("login", "adm_no", "sr_no", "email", "first_name", "second_name", "last_name","dob", "gender", "address", "postal", "city", "province", "country", "primary_contact", "mobile", "fax");
		$this->db->select($select);
		if ( ! empty ($members)) {
			$this->db->where_in ('member_id', $members);			
		} else {
			$this->db->where ('role_id', $role_id);			
		}
		$q = $this->db->get('members');
		//$response = $q->result();
 
		return ($q);
    }
    


	// count members of specific role
	public function countMemberInRole ($role_id=0, $coaching_id=0 ){
        if ($coaching_id > 0) {
    		$this->db->where('coaching_id', $coaching_id);
        }
		$this->db->where('role_id', $role_id);
		$query	=	$this->db->get('members');
		if ($query->num_rows() > 0) {
			$result = $query->num_rows();
		} else {
			$result = 0;
		}
		return $result;
	}
	
	// count members of specific status
	public function countMemberByStatus($status_id=0, $role_id=0, $coaching_id=0) {
		
        if ($coaching_id > 0) {
    		$this->db->where('coaching_id', $coaching_id);
        }
        if ($role_id > 0) {
			$this->db->where('role_id', $role_id);			
		}
		$this->db->where('status', $status_id);
		$query	=	$this->db->get('members');
		if ($query->num_rows() > 0) {
			$result = $query->num_rows();
		} else {
			$result = 0;
		}
		return $result;
	}

	// count members of specific batch
	public function countMemberByBatch($batch_id){
		$this->db->where('batch_id', $batch_id);
		$query = $this->db->get ('coaching_batch_users');		
		if($query->num_rows() > 0){
			$result = $query->num_rows();
		}else{
			$result = 0;
		}
		return $result;
	}	
	

    
	/* Auto Generate Reference ID */
	public function generate_reference_id ($member_id=0) {
		$prefix = array ();
		$ref_prefix	= $this->common_model->get_sys_parameters (SYS_REF_ID_PREFIX);
		foreach ($ref_prefix as $row) {
			$prefix[] = $row['paramkey'];
		}
		
		if ($member_id > 0) {
			// This means member record is already inserted and the primary key is passed as member_id
			$num = $member_id;
		} else {
			// Member record is not yet inserted, show a 
			$this->db->select_max ('member_id');
			$sql = $this->db->get ('members');
			if ($sql->num_rows () > 0) {
				$row = $sql->row_array ();
				$id = $row['member_id'];
			} else { 
				$id = 0;
			}
			$num = $id+1;
		}
		$suffix = str_pad($num, 5, "0", STR_PAD_LEFT);		
		$ref_id = implode ('', $prefix);
		$ref_id = $ref_id . $suffix;
		return $ref_id;
	}
	
	public function check_unique ($str='', $type='adm_no', $member_id=0) {
		
		$this->db->where ($type, $str);
		$sql = $this->db->get ('members');
		if ($member_id > 0){
			if ($sql->num_rows () > 1 ) {
				return true;
			} else{
				return false; 
			} 
		} else {
			if  ($sql->num_rows () > 0 ) {
				return true;
			} else {
				return false;
			} 			
		}
	}	

	public function file_download ($name) {
		
		$path = $this->config->item('temp_dir');
		$file = $path . $name;
		$data = read_file ($file);
		force_download($name, $data);
	}
	
	public function send_confirmation_email ($member_id=0, $coaching_id=0) {
		if ($this->session->userdata ('coaching_id')) {
			$coaching_id = $this->session->userdata ('coaching_id');
			$coaching 		= $this->coachings_model->get_coaching ($coaching_id);		
			$coaching_name  = $coaching['coaching_name'];
		} else {
			$coaching_name = SITE_TITLE;
		}
		$user_details	=	$this->get_user($member_id);
		$email 	= $user_details['email'];
		$userid = $user_details['login'];
		$crypt 	= md5 ($userid);
		$url	=	'login/page/create_password/'.$crypt;
		$link	=	anchor($url, 'Click here to create your password');
		$name 	= $user_details['first_name'].' '.$user_details['last_name'];
		$to 	= $email;
		$subject = 'Create Password';
		$message = '<strong> Greetings '.$name.'! </strong> 
					  <p>Your email is registered in our coaching <b>'.$coaching_name.'</b><br>
					  <p>Your login-id is:<b>'.$userid.'</b><br> 
					  '.$link.'. <br>
					  Alternatively, you can also copy-paste the following url in your browser.</p>
					  <p><strong>'.site_url($url).'</strong></p><br><br>
					  <p style="text-align:center; "><a style="padding:8px 12px; background:#4caf50; border: 1px solid #4caf50; color: white; text-decoration: none" href="'.site_url($url).'">Create your password</a></p>';
		$this->common_model->send_email ($to, $subject, $message, CONTACT_EMAIL, $coaching_name);
		// Update mail sent time
		$time = time ();
		$this->db->set ('link_send_time', $time);
		$this->db->where ('member_id', $member_id);
		$this->db->update ('members');
	}	
}