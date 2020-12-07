<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Report_actions extends MX_Controller {
		

	public function __construct () { 
		$config = ['config_coaching'];
	    $models = ['tests_reports', 'tests_model' ,'qb_model', 'users_model'];
	    $this->common_model->autoload_resources ($config, $models);

        $cid = $this->uri->segment (4);

        // Security step to prevent unauthorized access through url
        if ($this->session->userdata ('is_admin') == TRUE) {
        } else {
            if ($cid == true && $this->session->userdata ('coaching_id') <> $cid) {
                $this->message->set ('Direct url access not allowed', 'danger', true);
                redirect ('coaching/home/dashboard');
            }
        }
	}
	
	// SUBMISSIONS
	public function delete_submissions ($coaching_id=0, $category_id=0, $test_id=0) {
		$this->form_validation->set_rules ('users[]', 'Users ', 'required', ['required'=>'Select User(s)']);
		
		if ($this->form_validation->run () == true) {				
			$id = $this->tests_reports->delete_submissions ($coaching_id, $test_id);
			$message = 'Attempt deleted successfully';
			$redirect = site_url('coaching/reports/submissions/'.$coaching_id.'/'.$category_id.'/'.$test_id);
			$this->message->set ($message, 'success', true) ;
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>$redirect)));		
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
		
	}


}