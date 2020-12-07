<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {
    
    public function __construct () {
		$config = ['config_login'];
	    $models = ['login_model'];
	    $this->common_model->autoload_resources ($config, $models);
	    $this->load->helper ('file');
	}
	
 	public function index () {
    	
		$logo_path = $this->config->item ('system_logo');
		$logo = base_url ($logo_path);

    	$data['page_title'] = SITE_TITLE;
		$data['logo'] = $logo;
		
		$data['script'] = $this->load->view ('login/scripts/index', $data, true); 
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ('login/index', $data);
		$this->load->view (INCLUDE_PATH . 'footer', $data);

	}

	// For backward compatibility
	public function logout () {
		$this->session->sess_destroy ();
		$redirect = site_url ('admin/login/index');		
		redirect ($redirect);
	}

}