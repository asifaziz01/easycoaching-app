<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Subscription_actions extends MX_Controller {
	
	public function __construct () { 
	    $config = ['admin/config_admin'];
	    $models = ['admin/subscriptions_model'];
	    $this->common_model->autoload_resources ($config, $models);
	} 
	
	// Create Subscription Plans
	public function create_plan ($plan_id=0) {
		
		$this->form_validation->set_rules ('title', 'Title', 'required');
		$this->form_validation->set_rules ('price', 'Price', 'required');
		$this->form_validation->set_rules ('duration', 'Duration', 'required|numeric');
		$this->form_validation->set_rules ('max_users', 'Max Users', 'required|numeric');
		
		if ($this->form_validation->run () == true) {
			$this->subscriptions_model->create_plan ($plan_id);
			if ($plan_id > 0) {
				$message = 'Subscription plan updated successfully';
			} else {
				$message = 'Subscription plan created successfully';				
			}
			$redirect = 'admin/subscriptions/index';
			$this->message->set ($message, 'success', true);
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>site_url ($redirect) )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}

		
	// Delete Subscription Plans
	public function delete_plan ($plan_id=0) {
		$this->subscriptions_model->delete_plan ($plan_id);
		$this->message->set ('Plan deleted successfully', 'success', true); 
		redirect ('admin/subscriptions/index');
	}
}