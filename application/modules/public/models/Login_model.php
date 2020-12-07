 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
    
    public function validate_login ($slug='') {
		// this will validate if current user authentic to use resources
		// based on the received username and password
		$login		 	=  $this->input->post('username');
		$password		=  $this->input->post('password');
		$coaching_id 	=  $this->input->post ('coaching_id');

		$this->db->where ('coaching_id', $coaching_id);
		$where = "(login='$login' OR adm_no='$login' OR email='$login' OR primary_contact='$login')"; 
		$this->db->where ($where);
		$query = $this->db->get ("members");
		$row	=	$query->row_array();
		$return = array ();
		if ($query->num_rows() > 0) {
			$member_id 	= $row['member_id'];
			$role_id   	= $row['role_id'];
			$user_token = $row['user_token'];
			$user_name 	= $row['first_name'].' '.$row['second_name'].' '.$row['last_name'];
			$coaching_id = $row['coaching_id'];
			
			// This is a valid user,  check for password
			$hashed_password = $row['password'];
			if (password_verify($password, $hashed_password)) {
				// Reset wrong passwords attempted, if any
				$this->reset_wrong_password_attempts ($member_id);
				// Save Session 
				$this->save_login_session ($member_id, $role_id, $user_name, $coaching_id, $user_token, $slug);
				// Load menus 
				$menus = $this->load_menu ($role_id, 0);
				$return['status'] 		= LOGIN_SUCCESSFUL;
			} else {
				// User has input wrong password
				$wrong_attempts = $this->wrong_password_attempted ($member_id);
				if ($wrong_attempts >= MAX_WRONG_PASSWORD_ATTEMPTS) {
					$return['status'] = MAX_ATTEMPTS_REACHED;
				} else {
					$return['status'] = INVALID_PASSWORD;
				}
			} 
		} else {
			$return['status'] = INVALID_USERNAME;
		}
		return $return;
	}

	// Reset password attepted by a user in a day
	public function reset_wrong_password_attempts ($member_id=0) {
		$today = mktime (0, 0, 0, date ('m'), date ('d'), date ('Y'));
		$data = array ( 'member_id'=>$member_id,
						'att_date'=>$today);
		$this->db->where ($data);
		$this->db->delete ('password_attempts', $data);
	}

	// Set wrong password attepted by a user in a day
	public function wrong_password_attempted ($member_id=0) {
		$today = mktime (0, 0, 0, date ('m'), date ('d'), date ('Y'));
		$data = array ( 'member_id'=>$member_id,
						'att_date'=>$today,
					);
		$sql = $this->db->get_where ('password_attempts', $data);
		if ($sql->num_rows () > 0 ) {
			$this->db->set ('att_count', 'att_count+1');
			$this->db->where ($data);
			$this->db->update ('password_attempts');
		} else {
			$data['att_count'] = 1;
			$this->db->insert ('password_attempts', $data);
		}
	}
	

	public function save_login_session ($member_id=0, $role_id=0, $user_name="", $coaching_id=0, $user_token='', $slug='') {

		// Session
		$login_dt   	 = time ();
		$logout_dt  	 = "";
		$session_id 	 = "";
		$last_activity   = "";
		$ip_address   	 = $_SERVER['REMOTE_ADDR'];
		$user_agent 	 = "";
		$user_data	 	 = "";
		$status			 = "";
		$remarks		 = "";
		
		// get role details
		$this->db->where ('role_id', $role_id);
		$sql = $this->db->get ('sys_roles');			
		$roles = $sql->row_array ();			
		$role_level 	 = $roles['role_lvl'];
		$role_home  	 = $roles['dashboard'];
		$is_admin		 = $roles['admin_user'];
		
		// save login data to database			
		$this->db->insert ('login_history', array ('member_id'=>$member_id, 'login_dt'=>$login_dt, 'logout_dt'=>$logout_dt, 'session_id'=>$session_id, 'last_activity'=>$last_activity, 'ip_address'=>$ip_address, 'user_agent'=>$user_agent, 'user_data'=>$user_data, 'status'=>$status, 'remarks'=>$remarks) );
		
		if ($coaching_id > 0) {
		// Get coaching details
			$coaching = $this->coaching_model->get_coaching ($coaching_id);
			$coaching_dir = 'contents/coachings/' . $coaching_id . '/';
			$coaching_logo = $this->config->item ('coaching_logo');
			$logo_path =  $coaching_dir . $coaching_logo;
			$logo = base_url ($logo_path);
			$site_title = $coaching['coaching_name'];
		} else {
			$logo = base_url ($this->config->item('system_logo'));
			$site_title = SITE_TITLE;
		}
		$profile_image = $this->users_model->view_profile_image ($member_id);
	
		// Set user's session vars 
		$options = array(
						'member_id'		=> $member_id,
						'is_admin'		=> $is_admin,	
						'user_name'		=> $user_name,
						'status'		=> $status,
						'role_id'		=> $role_id,
						'role_lvl'		=> $role_level,
						'user_token'	=> $user_token,
						'dashboard'		=> $role_home,
						'is_logged_in'	=> true,
						'logo'			=> $logo,
						'site_title'	=> $site_title,
						'coaching_id'	=> $coaching_id,
						'profile_image'	=> $profile_image,
						'slug'			=> $slug,
						);
		
		$this->session->set_userdata ($options);
	}
	public function check_registered_email ($email, $coaching_id='') {
		$this->db->where ('email', $email);
		if ( ! empty($login)) {
			$this->db->where ('coaching_id', $coaching_id);		
		}
		$this->db->from ('members');
		$query = $this->db->get ();
		if ($query->num_rows () > 0 ) {
			return true;
		} else {
			return false;
		}		
	}
	public function get_member_by_login ($login) {
		$this->db->where ('login', $login);
		$this->db->or_where ('adm_no', $login);
		$sql = $this->db->get ('members');
		return $sql->row_array (); 
	}
	public function get_member_by_email_coaching_id ($email, $coaching_id) {
		$this->db->where ('email', $email);
		$this->db->or_where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('members');
		return $sql->row_array (); 
	}
	public function update_link_send_time ($login) {
		$current_time   =   time();
		$this->db->set('link_send_time', $current_time);
		$this->db->where('login', $login);
		$this->db->or_where('adm_no', $login);
		$this->db->update('members'); 
	}
	public function get_member_by_md5login ($login) {
		$sql = $this->db->get ('members');
		if ($sql->num_rows () > 0 ) { 
			$result = $sql->result_array ();
			foreach ($result as $row) {
				if (md5($row['login']) == $login) {
					return $row; 
				}
			}
		} else {
			return false;
		}			
	}
	public function load_menu ($role_id=0, $parent_id=0) {
		if ( ! $this->session->has_userdata ('MAIN_MENU')) {
    		$menus = $this->common_model->load_acl_menus ($role_id, $parent_id, MENUTYPE_SIDEMENU);
    		$this->session->set_userdata ('MAIN_MENU', $menus);
		}
		if ( ! $this->session->has_userdata ('DASHBOARD_MENU')) {
    		$menus = $this->common_model->load_acl_menus ($role_id, $parent_id, MENUTYPE_DASHBOARD);
    		$this->session->set_userdata ('DASHBOARD_MENU', $menus);
		}
		if ( ! $this->session->has_userdata ('FOOTER_MENU')) {
    		$menus = $this->common_model->load_acl_menus ($role_id, $parent_id, MENUTYPE_FOOTER);
    		$this->session->set_userdata ('FOOTER_MENU', $menus);
		}
	}
	public function logout () {
		$this->session->sess_destroy ();
		//$this->cookie->delete_cookie ();
	}
	public function update_password ($member_id) {
		// get user details
		$this->db->where ('member_id', $member_id);
		$sql = $this->db->get('members');
		$row = $sql->row_array ();
		if ($row['password'] == '') {
			// if user password is blank, then this is a first time user
			$this->db->set('status', USER_STATUS_ENABLED );
		}
		$password = $this->input->post ('password');
		$password = password_hash($password, PASSWORD_DEFAULT);
		$this->db->set('password', $password ); 
		$this->db->where('member_id', $member_id ); 
		$sql = $this->db->update('members');
		$this->db->last_query ();
		// reset_wrong_password_attempt is temporarily blocked
		// due to change of schema 
		//$this->reset_wrong_password_attempt($member_id);
	}
	
	public function reset_wrong_password_attempt($member_id){
		$this->db->where('member_id',$member_id);
		$data 	=	array(
						'wrong_password_attempts' => 0,
						'attempt_status'		  => 0,	
						'attempt_time'			  => time()		
					);
		$this->db->update('login_attempts',$data);
	}

}