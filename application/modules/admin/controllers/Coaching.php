<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coaching extends MX_Controller { 
    
    var $toolbar_buttons = []; 

    public function __construct () {
	    $config = ['config_admin'];
	    $models = ['coachings_model', 'subscriptions_model'];
	    $this->common_model->autoload_resources ($config, $models);
        $this->toolbar_buttons['<i class="fa fa-plus-circle"></i> New Coaching']= 'admin/coaching/create';
	}
    
    
    public function index () { 
		
		$data['page_title'] = 'Coachings';
		$data['sub_title']  = 'All Coachings';		

		$data['results'] = $this->coachings_model->get_all_coachings ();
		$data['data']	= $data;
		
		$data['bc'] = array ('Dashboard'=>'admin/home/dashboard');
		$data['toolbar_buttons'] = $this->toolbar_buttons;
		
		$data['script'] = $this->load->view ('coaching/script/index', $data, true);
		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('coaching/index', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);
	}	
	
	public function create ($coaching_id=0) {
		
		$data['coaching'] = $coaching = $this->coachings_model->get_coaching ($coaching_id);

		$data['bc'] = array ('Coachings'=>'coachings/admin/index');
		$data['coaching_id'] = $coaching_id;
		$data['page_title'] = 'Coaching';
		if ($coaching_id > 0) {
			$data['sub_title']  = 'Edit Coaching';
		} else {
			$data['sub_title']  = 'Add Coaching';
		}

		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('coaching/create', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);
	}

	/*--- ACCOUNT ---*/
	public function manage ($coaching_id=0) {
		$data['page_title'] = 'Manage Coaching Account';
		$data['coaching_id'] = $coaching_id;

		$data['bc'] = array ('Coachings'=>'admin/coaching/index');
		
		$data['coaching'] = $this->coachings_model->get_coaching_subscription ($coaching_id);
		$data['users'] = $users = $this->coachings_model->get_coaching_users ($coaching_id);
		$admins = [];
		if (! empty ($users)) {
			foreach ($users as $u) {
				if ($u['role_id'] == USER_ROLE_COACHING_ADMIN) {
					$admins[] = $u;
				}
			}
		}

		$data['admins'] = $admins;
		
		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('coaching/manage', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);

	}

	/* Upgrade/Change plan - Step 1: Browse all plans */
	public function browse_plans ($coaching_id=0, $current_plan=0) {
		
		$data['page_title'] = 'Browse Plans';
		
		$data['coaching_id'] = $coaching_id;
		$data['current_plan'] = $current_plan;
		$status = 1;
		$data['all_plans'] = $this->subscriptions_model->subscription_plans ($status);
		$data['cats_added'] = array ();

		$data['bc'] = array ('Coachings'=>'admin/coaching/manage/'.$coaching_id);
		$data["toolbar_buttons"] = array ();
		
		$data['coaching'] = $this->coachings_model->get_coaching ($coaching_id);
		
		$data['sys_dir'] = $this->config->item ('sys_dir') . 'coachings/' . $coaching_id . '/';
		$data['logo'] 	 = $this->config->item ('logo_file');
		
		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('coaching/browse_plans', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);		
	}	

}