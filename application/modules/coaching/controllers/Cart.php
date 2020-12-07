<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Cart extends MX_Controller {	

	public function __construct () {
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_coaching', 'config_cart'];
	    $models = ['subscription_model', 'coaching_model', 'users_model'];
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

	public function cart_items ($coaching_id=0) {

		$data['coaching_id']= $coaching_id;
		$data['page_title'] = "Select A Plan";
		$data['bc'] 		= array ('Browse Plans'=>'coaching/subscription/browse_plans/'.$coaching_id);
		$member_id 			= intval($this->session->userdata('member_id'));		
		
		$data['plan_id'] 	= $plan_id = $this->session->userdata ('cart');
		$data['plan'] 		= $plan = $this->subscription_model->subscription_plan ($plan_id);
		
		$member_id			= $this->session->userdata ('member_id');
		$data['user'] 		= $this->users_model->get_user ($member_id);

		$gst 				= $this->config->item ('gst');
		$data['gst_slab'] 	= $gst['gst_slab_18'];
		$data['plan_price']	= $plan['price'];
		
		$data['script']			= $this->load->view ('cart/scripts/cart_items', $data, true);
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('cart/cart_items', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	}
	
	public function checkout ($coaching_id=0, $plan_id) {
		redirect ('coaching/subscription_actions/change_plan/'.$coaching_id.'/'.$plan_id);
	}
	
	public function make_payment () {
		
		$data['page_title'] = "Make Payment";
		
		$data['coaching_id']= $this->input->post ('coaching_id');
		$data['plan_id']	= $this->input->post ('plan_id');
		$data['amount']		= $this->input->post ('amount');
		$data['gst']		= $this->input->post ('gst');
		
		$data['bc'] 		= array ('Cart'=>'coaching/cart/cart_items/'.$coaching_id.'/'.$plan_id);

		$member_id 			= intval($this->session->userdata('member_id'));
		$data['user'] 		= $this->users_model->get_user ($member_id);
		
		$data['plan_id'] 	= $plan_id = $this->session->userdata ('cart');
		$data['plan'] 		= $plan = $this->subscription_model->subscription_plan ($plan_id);
		
		//$data['script']			= $this->load->view ('cart/scripts/cart_items', $data, true);
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('cart/make_payment', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);		
	}
	
}