<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller {

    var $toolbar_buttons = []; 

    public function __construct () {
	    $config = ['config_admin'];
	    $models = ['coachings_model', 'subscriptions_model'];
	    $this->common_model->autoload_resources ($config, $models);
        $this->toolbar_buttons['<i class="fa fa-plus-circle"></i> New Coaching']= 'admin/coaching/create';
	}

    public function dashboard () {
		
		$data['page_title'] = 'Dashboard';
		$data['sub_title']  = 'Dashboard';

        $this->load->view (INCLUDE_PATH . 'header', $data);
		//echo 'Dashboard';
        $this->load->view (INCLUDE_PATH . 'footer');
	}
}