<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Cart_actions extends MX_Controller {	

	public function __construct () {
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_coaching'];
	    $models = ['subscription_model', 'coaching_model', 'users_model'];
	    $this->common_model->autoload_resources ($config, $models);
	}

	// Remove item from cart
	public function remove_item ($coaching_id=0, $plan_id=0) {

		unset($_SESSION['cart']);
		$this->message->set ('Plan removed from cart', 'success', true);
		redirect ('coaching/cart/cart_items/'.$coaching_id);
	}

	public function add_item ($coaching_id=0, $plan_id=0) {
		// Only single item in cart
		if ($plan_id > 0) {
			// Update cart
			$this->session->set_userdata ('cart', $plan_id);
		}
		
		$this->message->set ('Plan added to cart', 'success', true);
		redirect ('coaching/cart/cart_items/'.$coaching_id);
	}

}