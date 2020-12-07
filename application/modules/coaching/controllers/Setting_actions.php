<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Setting_actions extends MX_Controller {
	
	public function __construct () { 
	    $config = ['config_coaching'];
	    $models = ['coaching_model', 'settings_model'];
	    $this->common_model->autoload_resources ($config, $models);
	    $this->load->helper ('file');
	}

	public function update_account ($coaching_id=0) {
		$this->form_validation->set_rules ('coaching_name', 'Coaching Name ', 'required');
		$this->form_validation->set_rules ('address', 'Address ', 'required');
		$this->form_validation->set_rules ('city', 'City ', 'required');
		$this->form_validation->set_rules ('state', 'State ', 'required');
		$this->form_validation->set_rules ('pin', 'Pin ', 'required');
		$this->form_validation->set_rules ('website', 'Website', 'valid_url');
		
		if ($this->form_validation->run () == true) {				
			$id = $this->settings_model->update_account ($coaching_id);
			$message = 'Information updated successfully';
			$redirect = site_url('coaching/settings/index/'.$coaching_id);
			$this->message->set ($message, 'success', true) ;
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>$redirect)));		
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}

	public function upload_logo ($coaching_id=0) {
		$response = $this->settings_model->upload_logo ($coaching_id);
		if ($response == false) {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>'Logo uploaded successfully', 'redirect'=>site_url('coaching/settings/index') )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>$response )));		
		}
	}


	public function user_account ($coaching_id=0) {
		
		$this->settings_model->user_account ($coaching_id);
		$this->output->set_content_type("application/json");
		$this->output->set_output(json_encode(array('status'=>true, 'message'=>'Settings updated', 'redirect'=>'')));
	}


}
