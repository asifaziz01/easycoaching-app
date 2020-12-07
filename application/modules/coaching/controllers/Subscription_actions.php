<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Subscription_actions extends MX_Controller {	

	public function __construct () {
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_coaching'];
	    $models = ['subscription_model', 'coachings_model', 'users_model'];
	    $this->common_model->autoload_resources ($config, $models);
	}
	
	public function change_plan ($coaching_id=0, $plan_id=0) {		
		$this->subscription_model->change_plan ($coaching_id, $plan_id);
		$this->message->set ('Your subscription plan has been updated', 'success', true);
		redirect ('coaching/subscription/index/'.$coaching_id);
	}
}