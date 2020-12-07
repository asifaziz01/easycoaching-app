<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Subscription extends MX_Controller {
	
    var $toolbar_buttons = []; 

	public function __construct () {
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['coaching/config_coaching'];
	    $models = ['subscription_model', 'coaching_model'];
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
	
    public function index ($coaching_id=0) {
		$this->my_plans ($coaching_id);
	}
	
    public function my_plans ($coaching_id=0) {
				
		$data['page_title'] = 'Subscription';
		$data['sub_title'] = 'Subscribed Plan';
		$data['coaching_id'] = $coaching_id;

		$data['bc'] = array ('Coachings'=>'coaching/home/dashboard');
		
		//$data['coaching'] = $this->subscription_model->get_coaching ($coaching_id);
		$data['coaching'] = $this->subscription_model->get_coaching_subscription ($coaching_id);
		//$data['upgrade_requests'] = $this->subscription_model->upgrade_requests ($coaching_id);
		//$data['cats_added'] = array ();
		
		$data['role_id'] = $role_id = $this->session->userdata('role_id');
		
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ('subscription/my_plans', $data);
		$this->load->view (INCLUDE_PATH . 'footer', $data);		
	}

	/* Upgrade/Change plan - Step 1: Browse all plans */
	public function browse_plans ($coaching_id=0, $current_plan=0) {
		
		$data['page_title'] = 'Browse Plans';
		
		$data['coaching_id'] = $coaching_id;
		$data['current_plan'] = $current_plan;
		$status = 1;
		$paid_plans = 1;
		$data['all_plans'] = $this->subscription_model->subscription_plans ($status, $paid_plans);
		$data['cats_added'] = array ();

		$data['bc'] = array ('Coachings'=>'coaching/subscription/my_plans/'.$coaching_id);
		$data["toolbar_buttons"] = array ();
		
		$data['coaching'] = $this->coaching_model->get_coaching ($coaching_id);
		
		$data['sys_dir'] = $this->config->item ('sys_dir') . 'coachings/' . $coaching_id . '/';
		$data['logo'] 	 = $this->config->item ('logo_file');
		
		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('subscription/browse_plans', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);		
	}
	
	
	
	public function upgrade_plan ($coaching_id=0, $plan_id=0) {
		$data['plan_id'] 		= $plan_id;
		$data['coaching_id']	= $coaching_id;
		if ($coaching_id > 0) {
			$this->subscription_model->set_coaching_id ($coaching_id);
		} else {
			$coaching_id = $this->session->userdata ('coaching_id');
		}
		$data['coaching_plans'] = $this->subscription_model->coaching_plans ($coaching_id);
		$data['all_plans'] = $this->tests_model->subscription_plans ();
		$data['cats_added'] = array ();

		$data['bc'] = array ('Coachings'=>'coachings/admin/plans/'.$coaching_id);
		$data["toolbar_buttons"] = array ();
		
		$data['coaching'] = $this->subscription_model->get_coaching ($coaching_id);
		
		$data['sys_dir'] = $this->config->item ('sys_dir') . 'coachings/' . $coaching_id . '/';
		$data['logo'] 	 = $this->config->item ('logo_file');

		/* --==// Sidebar //==-- */ 
		$data['sidebar']		= $this->load->view ('sidebar/coachings', $data, true);
		
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('upgrade_plan', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
		
	}
	
	public function view_plan ($coaching_id=0, $plan_id=0) {
		$this->load->model ('plans/plans_model');
		
		$data['coaching_id'] 	= $this->session->userdata ('coaching_id');
		$data['plan_id'] 		= $plan_id;
		$data['plan']			= $this->plans_model->single_subscription_plan ($plan_id);
		$data['page_title'] 	= "Select A Plan";
		$data['bc'] 			= array ('Browse Plans'=>'coachings/admin/plans');
		
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('view_plan', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
		
	}
	

}