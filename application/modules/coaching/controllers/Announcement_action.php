<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Announcement_action extends MX_Controller {	

	public function __construct () {
		
	    // Load Config and Model files required throughout Users sub-module
	    $config = [ 'config_coaching'];
	    $models = ['announcements_model'];
	    $this->common_model->autoload_resources ($config, $models);
	    
	}
	 public function create ($coaching_id=0, $announcement_id=0){
	 	$this->form_validation->set_rules ('title', 'Title', 'required|trim');
	 	if ($this->form_validation->run () == true) {

	 		$this->announcements_model->create_announcement($coaching_id,$announcement_id);
	 		$this->output->set_content_type("application/json");
				$this->output->set_output(json_encode(array('status'=>true, 'message'=>'Announcement created successfully' ,'redirect'=>site_url('coaching/announcements/index/'.$coaching_id))));
	 	}
	 	else {
	 		$this->output->set_content_type("application/json");
				$this->output->set_output(json_encode(array('status'=>false, 'error'=> validation_errors())));
	 	}


	 }

	 public function delete ($coaching_id=0, $announcement_id=0) {
		$this->announcements_model->delete_announcement ($coaching_id,$announcement_id);
		$this->message->set ('Announcement deleted successfully', 'success', true);
		redirect ('coaching/announcements/index/'.$coaching_id);
	}


}