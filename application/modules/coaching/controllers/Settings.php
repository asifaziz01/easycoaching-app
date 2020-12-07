<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Settings extends MX_Controller {
	
	public function __construct () { 
	    $config = ['config_coaching'];
	    $models = ['coaching_model', 'settings_model'];
	    $this->common_model->autoload_resources ($config, $models);
	    $this->load->helper ('file');

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
		if ($coaching_id == 0) {
			$coaching_id = $this->session->userdata ('coaching_id');
		}
		$this->general ($coaching_id);
	}
	
	/* GENERAL SETTINGS PAGE */
	public function general ($coaching_id=0) {
		$data['page_title'] = 'General Settings';
		/* Back Link */
		
		$data['bc'] = array ('Dashboard'=>'coaching/home/dashboard/'.$coaching_id);
		$coaching = $this->coaching_model->get_coaching ($coaching_id);
		$settings = $this->settings_model->get_settings ($coaching_id);
		
		$coaching_dir = 'contents/coachings/' . $coaching_id . '/';
		$coaching_logo = $this->config->item ('coaching_logo');
		$logo_path =  $coaching_dir . $coaching_logo;
		$logo = base_url ($logo_path);
		if (read_file ($logo)) {
			$is_logo = true;
		} else {
			$is_logo = false;
		}

		$data['logo'] 	 = $logo;
		$data['is_logo'] 	 = $is_logo;

		$data['rand_str'] = time ();
		$data['coaching'] = $coaching;
		$data['settings'] = $settings;
		$data['coaching_id'] = $coaching_id;
		
		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('settings/index', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);
	}
}