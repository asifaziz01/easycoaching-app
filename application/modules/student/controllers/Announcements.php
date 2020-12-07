<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Announcements extends MX_Controller {

   
	public function __construct () {
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_student'];
	    $models = ['announcements_model'];

	    $this->common_model->autoload_resources ($config, $models);
	    
        $cid = $this->uri->segment (4);        
        
        // Security step to prevent unauthorized access through url
        if ($this->session->userdata ('is_admin') == TRUE) {
        } else {
            if ($cid == true && $this->session->userdata ('coaching_id') <> $cid) {
                $this->message->set ('Direct url access not allowed', 'danger', true);
                redirect ('student/home/dashboard');
            }
        }
	}

	public function index ($coaching_id=0, $status=0) { 


		if ($coaching_id==0) {
            $coaching_id = $this->session->userdata ('coaching_id');
        }
            $member_id = $this->session->userdata ('member_id');

		/*---=== Back Link ===---*/
		$data['bc'] = array ('Student Dashboard'=>'student/home/dashboard/'.$coaching_id);
		$data['results'] = $this->announcements_model->get_announcements ($coaching_id);
		$data['coaching_id'] = $coaching_id;
		$data['member_id'] = $member_id;
		$data['page_title']  = 'Announcements';
			
		$this->load->view ( INCLUDE_PATH  . 'header', $data);
		$this->load->view ( 'annc/index', $data);
		$this->load->view ( INCLUDE_PATH  . 'footer', $data);	
	}

	public function view ($coaching_id=0, $annc_id=0) { 

		if ($coaching_id==0) {
            $coaching_id = $this->session->userdata ('coaching_id');
        }
        $member_id = $this->session->userdata ('member_id');

		$data['coaching_id'] = $coaching_id;
		$data['member_id'] = $member_id;
		$data['page_title']  = 'Announcement';
		
		/*---=== Back Link ===---*/
		$data['bc'] = array ('Announcements'=>'student/announcements/index/'.$coaching_id);
		$data['row'] = $this->announcements_model->get_announcement ($coaching_id, $annc_id);
			
		$this->load->view ( INCLUDE_PATH  . 'header', $data);
		$this->load->view ( 'annc/view', $data);
		$this->load->view ( INCLUDE_PATH  . 'footer', $data);	
	}
}