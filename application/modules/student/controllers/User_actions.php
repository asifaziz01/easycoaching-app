<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class User_actions extends MX_Controller {
	
    var $toolbar_buttons = []; 

	public function __construct () {
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_student'];
	    $models = ['coaching_model', 'users_model'];
	    $this->common_model->autoload_resources ($config, $models);
	    $this->load->helper ('string');
	}
	
	   
	// EDIT MY ACCOUNT
	public function my_account ($coaching_id=0, $member_id=0) {
	
		$this->form_validation->set_rules ('first_name', 'First Name', 'required|max_length[50]|trim|ucfirst');
		$this->form_validation->set_rules ('second_name', 'Second Name', 'max_length[50]|trim');
		$this->form_validation->set_rules ('last_name', 'Last Name', 'max_length[50]|trim');
		$this->form_validation->set_rules ('email', 'Email', 'valid_email');
		$this->form_validation->set_rules ('primary_contact', 'Primary Contact', 'required|is_natural|trim');
		$this->form_validation->set_rules('dob', 'Date of Birth', '');
		$this->form_validation->set_rules('gender', 'Gender', '');

		if ($this->form_validation->run () == true) {
			$id = $this->users_model->save_account ($coaching_id, $member_id);
			$message = 'Account updated successfully';
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>site_url('student/users/my_account/'.$coaching_id.'/'.$member_id) )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}
	
	
	/* PROFILE PICTURE */
	// UPLOAD IMAGE
	public function upload_profile_picture ($member_id=0, $coaching_id=0) {
		$response = $this->users_model->upload_profile_picture ($member_id);
		if (is_array($response)) {		// Upload successful
			$profile_image = $this->users_model->view_profile_image ($member_id);
            $this->session->set_userdata ('profile_image', $profile_image);
            $redirect = site_url ('student/users/my_account/'.$coaching_id.'/'.$member_id);
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>'Profile picture uploaded successfully', 'redirect'=>$redirect )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>$response )));
		}
	}
	
	// DISPLAY IMAGE AFTER UPLOAD
	public function displayprofileimage ($member_id) {
		$profile_image = $this->users_model->view_profile_image ($member_id);
	} 
	
	/* REMOVE PROFILE IMAGE	*/
	public function remove_profile_image ($member_id=0, $coaching_id=0 ) {
		$user = $this->users_model->get_user ($member_id);
		$this->users_model->remove_profile_image ($member_id);
		$this->message->set ('Profile image removed successfully', 'success', true);
        redirect ('student/users/my_account/'.$coaching_id.'/'.$user['role_id'].'/'.$member_id);
	}
	
	
	/* CHANGE USER PASSWORD
		Function to change password of selected user
	*/
	public function change_password ($coaching_id=0, $member_id) {		

		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[50]');			
		$this->form_validation->set_rules('repeat_password', 'Repeat Password', 'required|matches[password]');
		
		if ($this->form_validation->run() == true) {
			$password = $this->input->post ('password');
			$this->users_model->update_password ($member_id, $password); 
			$user = $this->users_model->get_user ($member_id);

			// Display message
			$this->message->set('Password changed successfully', 'success', true);			

			// Send SMS
			$data = [];
			$contact = $user['primary_contact'];
			$message = $this->load->view (SMS_TEMPLATE . 'change_password', $data, true);
			$this->sms_model->send_sms ($contact, $message);

			// Send Email
			if ($user['email'] != '') {
				$data = [];
				$email = $user['email'];
				$subject = "Password Changed";
				$message = $this->load->view (EMAIL_TEMPLATE . 'change_password', $data, true);
				$this->common_model->send_email($send_to, $subject, $message );				
			}

			$redirect = site_url ('student/users/my_account/'.$coaching_id.'/'.$member_id);
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>'Password changed successfully', 'redirect'=>$redirect )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}
	

	public function send_otp ($coaching_id=0, $member_id=0) {
		
		$otp = $this->users_model->reset_password ($member_id);
		$user = $this->users_model->get_user ($member_id);
		$coaching = $this->coaching_model->get_coaching ($coaching_id);

		$data['coaching_name'] = $coaching['coaching_name'];
		$data['otp'] = $otp;
		$message = $this->load->view (SMS_TEMPLATE . 'reset_password', $data, true);
		$contact = $user['primary_contact'];
		$this->sms_model->send_sms ($contact, $message);

		// Send Email
		if ($user['email'] != '') {
			$email = $user['email'];
			$subject = "OTP Login";
			$message = $this->load->view (EMAIL_TEMPLATE . 'reset_password', $data, true);
			$this->common_model->send_email($send_to, $subject, $message );				
		}
	
		$this->output->set_content_type("application/json");
		$this->output->set_output(json_encode(array('status'=>true, 'message'=>'OTP sent on user mobile', 'redirect'=>'')));
	}
}