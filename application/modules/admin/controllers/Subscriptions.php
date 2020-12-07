<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Subscriptions extends MX_Controller {

    var $toolbar_buttons = []; 

    public function __construct () {
	    $config = ['config_admin'];
	    $models = ['subscriptions_model'];
	    $this->common_model->autoload_resources ($config, $models);
        
        $this->toolbar_buttons['<i class="fa fa-plus-circle"></i> New Plan']= 'admin/subscriptions/create_plan';
	}
    
	/*-----------===============SUBSCRIPTION PLANS=============---------------*/
	public function index () {
	    $this->subscription_plans ();    
	}
	
	/* Subscription Plans */
	public function subscription_plans () {
		
		$data['page_title'] = 'Subscription Plans';
		$data['sub_title'] = 'All Subscription Plans';
		$data['subscription_plans'] = $this->subscriptions_model->subscription_plans ();
		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$data['bc'] = array ('Plans'=>'admin/home/dashboard');

		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ('subscriptions/plans', $data);
		$this->load->view (INCLUDE_PATH . 'footer', $data);
	}


	/* Create Subscription Plans */
	public function create_plan ($plan_id=0) {
		
		$data['page_title'] = 'Subscription Plans';
		$data['page_title'] = 'Create Subscription Plan';
		
		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$data['plan'] = $this->subscriptions_model->subscription_plan ($plan_id);
		$data['plan_id'] = $plan_id;
		$data['bc'] = array ('Subscription Plans'=>'admin/subscriptions/subscription_plans');

		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ('subscriptions/create_plan', $data);
		$this->load->view (INCLUDE_PATH . 'footer', $data);
	}
	
}