<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Users extends MX_Controller {
	
    var $toolbar_buttons = []; 

	public function __construct () {
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_student'];
	    $models = ['users_model'];
	    $this->common_model->autoload_resources ($config, $models);
	    
        $cid = $this->uri->segment (4);
        
        // Security step to prevent unauthorized access through url
        if ($this->session->userdata ('is_admin') == TRUE) {
        } else {
            if ($this->session->userdata ('coaching_id') <> $cid) {
                $this->message->set ('Direct url access not allowed', 'danger', true);
                redirect ('student/home/dashboard');
            }
        }
	}
	
	
	
	/*----------- MY ACCOUNT FUNCTIONS -------------*/
	public function my_account ($coaching_id=0, $member_id=0) {
		
		$data['page_title'] = 'My Account';
		if ($member_id == 0) {
			$member_id = $this->session->userdata ('member_id');
		}
		
		$data['member_id'] 		= $member_id;
		$data['profile_image'] 	= $this->users_model->view_profile_image ($member_id);
		$user 					= $this->users_model->get_user ($member_id);
		$user_profile 			= $this->users_model->member_profile ($member_id); 
		$batches 				= $this->users_model->member_batches ($member_id); 
		
		if ( is_array ($user) ) {
			$data['result'] 	= array_merge ($user, $user_profile);			
		} else {
			$data['result'] 	= false;
		}		
		$data['coaching_id'] 	= $coaching_id;
		$data['role_id'] 		= $user['role_id'];
		$data['batches'] 		= $batches;
		$data['roles']	 		= $this->users_model->user_role_name ($user['role_id']);
		$data['rand_str'] 		= time ();
		
		/* Breadcrumbs */
		$data['bc'] = array ('Dashboard'=>'student/home/dashboard/'.$coaching_id);		
		$data['data'] = $data;
		
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ('user/my_account', $data);
		$this->load->view (INCLUDE_PATH . 'footer', $data);
	}
	
	/*----------- MY PASSWORD ----------------*/
	public function my_password ($coaching_id=0, $member_id=0) {
		$data['result'] = $this->users_model->get_user ($member_id);
		$data['profile_image'] = $this->users_model->view_profile_image ($member_id);
		$data['page_title'] = 'Change Password'; 
		$data['member_id']  = $member_id;       
		$data['coaching_id']   = $coaching_id;
		$data["bc"] = array ( 'Users'=>'student/users/my_account/'.$coaching_id.'/'.$member_id );
		$data['toolbar_buttons'] = $this->toolbar_buttons;

		$data['data'] = $data;
		
		$data['script'] = $this->load->view ('user/scripts/change_password', $data, true);
		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('user/my_password', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);
	}	

}