<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {
    
    public function __construct () {
		$config = ['config_login'];
	    $models = ['login_model'];
	    $this->common_model->autoload_resources ($config, $models);
	}
	
    public function index () {
    	$this->login ();
	}

    public function login () {

    	$data['site_title'] = SITE_TITLE;
		$data['page_title'] = 'Login';
		
		$data['script'] = $this->load->view ('scripts/admin-login', $data, true); 
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ('admin-login', $data);
		$this->load->view (INCLUDE_PATH . 'footer', $data);
    }	
	
	
	public function logout () {
		$this->login_model->logout ();
		redirect ('login/admin/index');
	}
	
}