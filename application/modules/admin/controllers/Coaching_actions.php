<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Coaching_actions extends MX_Controller {
	
	public function __construct () { 
	    $config = ['admin/config_admin', 'coaching/config_coaching'];
	    $models = ['admin/coachings_model', 'coaching/users_model'];
	    $this->common_model->autoload_resources ($config, $models);

		$this->load->dbutil ();
		$this->load->dbforge ();
	} 
	
	public function search_coaching () {
		
		$data['results'] = $this->coachings_model->search_coaching ();

		$coachings = $this->load->view ('coaching/inc/index', $data, true);
		$this->output->set_content_type("application/json");
		$this->output->set_output(json_encode(array('status'=>true, 'data'=>$coachings)));
	}
	
	public function create_account ($coaching_id=0) {
	
        $this->load->helper('string');
		
		$this->form_validation->set_rules ('coaching_name', 'Coaching Name ', 'required');
		$this->form_validation->set_rules ('city', 'City ', 'required');
		$this->form_validation->set_rules ('website', 'Website', 'valid_url');
        if ($coaching_id == 0) {
    		$this->form_validation->set_rules ('first_name', 'First Name', 'max_length[100]|trim|ucfirst');		
    		$this->form_validation->set_rules ('last_name', 'Last Name', 'max_length[100]|trim|ucfirst');
    		$this->form_validation->set_rules ('email', 'Email', 'required|valid_email');
    		$this->form_validation->set_rules ('primary_contact', 'Contact No', 'required|is_natural|trim');
        }
        
		if ($this->form_validation->run () == true) { 
			$email = $this->input->post ('email');
			if ($coaching_id == 0 && $this->users_model->check_unique ($email, 'email')) {
				$this->output->set_content_type("application/json");
				$this->output->set_output(json_encode(array('status'=>false, 'error'=>'The email <strong>'.$email.'</strong> is already registered. Please provide another email.' )));
            } else {
    			$id = $this->coachings_model->create_coaching_account ($coaching_id);
    			$message = 'Coaching account created successfully. You can '.anchor ('coaching/subscription/index/'.$id, 'add subscription plan here');
    			$redirect = site_url('admin/coaching/index');
    			$this->message->set ($message, 'success', true) ;
    			$this->output->set_content_type("application/json");
    			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>$redirect)));
            }
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}
	

	public function save_settings ($coaching_id=0) {
		$this->form_validation->set_rules ('coaching_name', 'Coaching Name ', 'required');
		$this->form_validation->set_rules ('address', 'Address ', 'required');
		$this->form_validation->set_rules ('city', 'City ', 'required');
		$this->form_validation->set_rules ('state', 'State ', 'required');
		$this->form_validation->set_rules ('pin', 'Pin ', 'required');
		$this->form_validation->set_rules ('website', 'Website', 'valid_url');
		
		if ($this->form_validation->run () == true) {				
			$id = $this->coachings_model->save_settings ($coaching_id);
			$message = 'Account updated successfully';
			$redirect = site_url('coachings/admin/settings');				
			$this->message->set ($message, 'success', true) ;
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>$redirect)));		
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}


	public function edit_plan ($coaching_id=0, $plan_id=0) {
		$this->form_validation->set_rules ('start_date', 'Starting From ', 'required');
		$this->form_validation->set_rules ('end_date', 'Ending On ', 'required');
		
		if ($this->form_validation->run () == true) {				
			$id = $this->coachings_model->edit_plan ($coaching_id, $plan_id);
			$message = 'Plan updated successfully';
			$redirect = site_url('admin/coaching/manage/'.$coaching_id);
			$this->message->set ($message, 'success', true) ;
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>$redirect)));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}
	/* DELETE ACCOUNT
		Function to delete existing user accounts
	*/
	public function delete_account ($coaching_id) {
		$this->coachings_model->delete_account ($coaching_id);
		$this->message->set ('Coaching account deleted successfully', 'success', true);
		redirect('admin/coaching/index');
	}
	
	/*========================================================================*/

	public function upgrade_plan ($coaching_id=0, $plan_id=0) {		
		$this->coachings_model->upgrade_plan ($coaching_id, $plan_id);
		$this->message->set ('Coaching subscription plan has been updated', 'success', true);
		redirect ('admin/coaching/manage/'.$coaching_id);
	}
	
	
}