<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller {
    
    public function __construct () {
		$config = ['config_login'];
	    $models = ['coaching/coaching_model', 'login_model'];	    
	    $this->common_model->autoload_resources ($config, $models);
	    $this->load->helper ('file');
	}
	
 	public function index () {
    	// Default settings
		$access_code = '';
		$logo_path = $this->config->item ('system_logo');
		$logo = base_url ($logo_path);
		$page_title = 'Sign-In';
		$found = false;

		if (isset ($_GET['sub']) && ! empty ($_GET['sub']) && $_GET['sub'] != 'undefined') {
    		$access_code = $_GET['sub'];
    		$coaching = $this->coaching_model->get_coaching_by_slug ($access_code);
			if ($coaching) {
				$coaching_dir = 'contents/coachings/' . $coaching['id'] . '/';
				$coaching_logo = $this->config->item ('coaching_logo');
				$logo_path =  $coaching_dir . $coaching_logo;
				$logo = base_url ($logo_path);
				$page_title = $coaching['coaching_name'];
				$found = true;
			}
    	}

		$data['page_title'] = $page_title;
		$data['logo'] = $logo;
		$data['access_code'] = $access_code;
		$data['found'] = $found;
		
		$data['script'] = $this->load->view ('scripts/login', $data, true); 
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ('login', $data);
		$this->load->view (INCLUDE_PATH . 'footer', $data);
    }	
	
	/* Register Page */
	public function register () {

		// Default settings
		$access_code = '';
		$logo_path = $this->config->item ('system_logo');
		$logo = base_url ($logo_path);
		$page_title = SITE_TITLE;

		if (isset ($_GET['sub']) && ! empty ($_GET['sub']) && $_GET['sub'] != 'undefined') {
    		$access_code = $_GET['sub'];
    		$coaching = $this->coaching_model->get_coaching_by_slug ($access_code);
			if ($coaching) {
				$coaching_dir = 'contents/coachings/' . $coaching['id'] . '/';
				$coaching_logo = $this->config->item ('coaching_logo');
				$logo_path =  $coaching_dir . $coaching_logo;
				$logo = base_url ($logo_path);
				$page_title = $coaching['coaching_name'];
			}
    	}		

    	if (isset ($_GET['role']) && ! empty ($_GET['role'])) {
    		$role_id = $_GET['role'];
    	} else {
    		$role_id = USER_ROLE_STUDENT;
    	}

		$data['access_code'] = $access_code;
		$data['page_title'] = $page_title;
		$data['logo'] = $logo;
		$data['role_id'] = $role_id;
		
		$data['script'] = $this->load->view ('scripts/register', $data, true); 
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ( 'register', $data); 
		$this->load->view (INCLUDE_PATH . 'footer', $data);
	}

	
	/* forgot password */
	public function reset_password () {
		// Default settings
		$access_code = '';
		$logo_path = $this->config->item ('system_logo');
		$logo = base_url ($logo_path);
		$page_title = SITE_TITLE;

		if (isset ($_GET['sub']) && ! empty ($_GET['sub']) && $_GET['sub'] != 'undefined') {
    		$access_code = $_GET['sub'];
    		$coaching = $this->coaching_model->get_coaching_by_slug ($access_code);
			if ($coaching) {
				$coaching_dir = 'contents/coachings/' . $coaching['id'] . '/';
				$coaching_logo = $this->config->item ('coaching_logo');
				$logo_path =  $coaching_dir . $coaching_logo;
				$logo = base_url ($logo_path);
				$page_title = $coaching['coaching_name'];
			}
    	}

		$data['page_title'] = $page_title;
		$data['logo'] 		= $logo;
		$data['access_code']= $access_code;
		$data['page_title'] = $page_title;
		
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view('reset_password');		
		$this->load->view (INCLUDE_PATH . 'footer', $data);
	}

	/* forgot password */
	public function get_access_code () {
		// Default settings
		$access_code = '';
		$logo_path = $this->config->item ('system_logo');
		$logo = base_url ($logo_path);
		$page_title = SITE_TITLE;

		$data['page_title'] = $page_title;
		$data['logo'] 		= $logo;
		$data['access_code']= $access_code;
		$data['page_title'] = $page_title;
		
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view('get_access_code');		
		$this->load->view (INCLUDE_PATH . 'footer', $data);
	}

	/* create password for new user register */
	public function create_password ($user_id='') {
		if (isset ($_GET['sub']) && ! empty ($_GET['sub'])) {
    		$slug = $_GET['sub'];
			$coaching = $this->coachings_model->get_coaching_by_slug ($slug);
			if ($coaching) {
				$coaching_dir = 'contents/coachings/' . $coaching['id'] . '/';
				$coaching_logo = $this->config->item ('coaching_logo');
				$logo_path =  $coaching_dir . $coaching_logo;
				$logo = base_url ($logo_path);
				$page_title = 'Create Password ' . $coaching['coaching_name'];				
			} else {
				$slug = '';
	    		$logo = base_url ($this->config->item('system_logo'));
				$page_title = 'Create Password ' . SITE_TITLE;
			}
    	} else {    		
    		$slug = '';    		
    		$logo = base_url ($this->config->item('system_logo'));
			$page_title = 'Create Password ' . SITE_TITLE;
    	}
    	if ( empty ($slug)) {
			$this->message->set ('Direct registration not allowed', 'danger', true);
			redirect ('login/page/index');    		
    	}
		$result			=	$this->login_model->get_member_by_md5login ($user_id);
		$coaching_id	=	$result['coaching_id'];
		
		$link_send_time	=	$result['link_send_time'];
		$difference		=	time() - $link_send_time;
		if ($difference > 3600*48) {		// Email link is valid only for 48 hours
			echo 'This link has expired. Please '.anchor ('login/page/forgot_password', 'Try again');
		} else {
			$coaching = $this->coachings_model->get_coaching($coaching_id );
			$data['coaching_id'] 		= $coaching_id;
			$data['coaching'] 			= $coaching;
			$data['member_id'] 			= $result['member_id'];
			$data['result'] 			= $result;
			$data['hide_left_sidebar'] 	= true;
			$data['page_title'] = $page_title;
			$data['slug'] = $slug;
			$data['logo'] = $logo;
			$this->load->view( INCLUDE_PATH . 'header', $data);
			$this->load->view( 'password', $data);
			$this->load->view( INCLUDE_PATH . 'footer', $data);
		}
	}	

	/* forget password */
	public function _forgot_password (){
		if ($this->session->userdata('is_logged_in')) {
			$this->login_model->logout ();
		}
		if (isset ($_GET['sub']) && ! empty ($_GET['sub'])) {
    		$slug = $_GET['sub'];
			$coaching = $this->coaching_model->get_coaching_by_slug ($slug);
			if ($coaching) {
				$coaching_dir = 'contents/coachings/' . $coaching['id'] . '/';
				$coaching_logo = $this->config->item ('coaching_logo');
				$logo_path =  $coaching_dir . $coaching_logo;				
				$logo = base_url ($logo_path);

				$page_title = 'Forgot Password ' . $coaching['coaching_name'];
				$data['coaching_id'] = $coaching['id'];
				$data['coaching'] 			= $coaching;
			} else {
				$slug = '';
	    		$logo = base_url ($this->config->item('system_logo'));
				$page_title = 'Forgot Password ' . SITE_TITLE;
			}
    	} else {
    		$slug = '';
    		$logo = base_url ($this->config->item('system_logo'));
			$page_title = 'Forgot Password ' . SITE_TITLE;
    	}
		$data['page_title'] 		= $page_title;
		$data['slug'] = $slug;
		$data['logo'] = $logo;
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view('forgot_password');		
		$this->load->view (INCLUDE_PATH . 'footer', $data);
	}

	public function otp_request($slug='') {
    	$not_found = false;
    	if (isset ($_GET['sub']) && ! empty ($_GET['sub'])) {
    		$slug = $_GET['sub'];
	    	$coaching = $this->coaching_model->get_coaching_by_slug ($slug);
	    	if ($coaching) {
	    		$coaching_dir = 'contents/coachings/' . $coaching['id'] . '/';
				$coaching_logo = $this->config->item ('coaching_logo');
				$logo_path =  $coaching_dir . $coaching_logo;				
				$logo = base_url ($logo_path);

				$page_title = $coaching['coaching_name'];
				$data['slug'] = $slug;
				$data['logo'] = $logo;
				$data['coaching_id'] = $coaching['id'];
				$data['page_title'] = $page_title;

				$data['script'] = $this->load->view ('scripts/otp_request', $data, true); 
				$this->load->view (INCLUDE_PATH . 'header', $data);
				$this->load->view ('otp_request', $data);
				$this->load->view (INCLUDE_PATH . 'footer', $data);

	    	} else {
				$not_found = true;
			}
    	} else {
    		$not_found = true;
    	}
    	if ($not_found) {
    		redirect('coaching/login/find_coaching');
    	}
	}
	public function otp_verify($member_id=0, $slug='') {
		$not_found = false;
    	if (isset ($_GET['sub']) && ! empty ($_GET['sub'])) {
    		$slug = $_GET['sub'];
	    	$coaching = $this->coaching_model->get_coaching_by_slug ($slug);
	    	if ($coaching) {
	    		$coaching_dir = 'contents/coachings/' . $coaching['id'] . '/';
				$coaching_logo = $this->config->item ('coaching_logo');
				$logo_path =  $coaching_dir . $coaching_logo;				
				$logo = base_url ($logo_path);

				$page_title = $coaching['coaching_name'];
				$data['slug'] = $slug;
				$data['logo'] = $logo;
				$data['coaching_id'] = $coaching['id'];
				$data['member_id'] = $member_id;
				$data['page_title'] = $page_title;

				$data['script'] = $this->load->view ('scripts/otp_verify', $data, true); 
				$this->load->view (INCLUDE_PATH . 'header', $data);
				$this->load->view ('otp_verify', $data);
				$this->load->view (INCLUDE_PATH . 'footer', $data);

	    	} else {
				$not_found = true;
			}
    	} else {
    		$not_found = true;
    	}
    	if ($not_found) {
    		redirect('coaching/login/find_coaching');
    	}
	}

}