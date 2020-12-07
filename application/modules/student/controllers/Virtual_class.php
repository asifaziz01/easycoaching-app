<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Virtual_class extends MX_Controller {	

	var $toolbar_buttons = [];

	public function __construct () {		
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_student', 'config_virtual_class'];
	    $models = ['virtual_class_model', 'users_model'];
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

	public function index ($coaching_id=0, $member_id=0) {
		$this->my_classroom ($coaching_id, $member_id);
	}

	public function my_classroom ($coaching_id=0, $member_id=0) {
		
		if ($coaching_id == 0) {
			$coaching_id = $this->session->userdata ('coaching_id');
		}
		
		if ($member_id == 0) {
			$member_id = $this->session->userdata ('member_id');
		}

		$data['coaching_id'] = $coaching_id;
		$data['member_id'] = $member_id;
		$data['bc'] = array('Dashboard'=>'student/home/dashboard/'.$coaching_id);
		$data['class'] = $this->virtual_class_model->my_classroom ($coaching_id, $member_id);

        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/my_classroom', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);		
	}	

	public function join_class ($coaching_id=0, $class_id=0, $member_id=0) {
		
		$api_setting = $this->virtual_class_model->get_api_settings ('join_url');
		$class = $this->virtual_class_model->get_class ($coaching_id, $class_id);
		$join = $this->virtual_class_model->join_class ($coaching_id, $class_id, $member_id);
		$meeting_url = $join['meeting_url'];
		$api_join_url = $api_setting['join_url'];
		$join_url = $api_join_url . $meeting_url;
		
		$is_running = $this->virtual_class_model->is_meeting_running ($coaching_id, $class_id);

		if ( $is_running == 'true') {
			redirect ($join_url);
		} else {
			if ($join['role'] == VM_PARTICIPANT_MODERATOR)  {
				$response = $this->virtual_class_model->create_meeting ($coaching_id, $class_id);
				if ($response == 'SUCCESS') {
					redirect ($join_url);
				}
			}
		}

		$data['coaching_id'] = $coaching_id;
		$data['class_id'] = $class_id;
		$data['member_id'] = $member_id;
		$data['page_title'] = 'Classroom Not Started';
		$data['bc'] = array('Virtual Classroom'=>'student/virtual_class/index/'.$coaching_id.'/'.$member_id);

        $data['script'] = $this->load->view('virtual_class/scripts/error', $data, true);
        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/error', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);		
	}

	public function recordings ($coaching_id=0, $class_id=0, $meeting_id=0) {		

		$api_setting = $this->virtual_class_model->get_api_settings ();
		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$class = $this->virtual_class_model->get_class ($coaching_id, $class_id);

		if ($class['recording_for_students'] != 'true') {
			$this->message->set ('Recordings not allowed', 'success', true);
			redirect ('student/virtual_class/my_classroom/'.$coaching_id.'/'.$class_id);
		}

		// Create call and query
		$api_url = $api_setting['api_url'];
		$shared_secret = $api_setting['shared_secret'];

		$call_name = 'getRecordings';
		$query_string = 'meetingID='.$class['meeting_id'];
		$final_string = $call_name . $query_string . $shared_secret;
		$checksum = sha1($final_string);

		$url = $api_url . $call_name . '?' . $query_string . '&checksum='.$checksum;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$xml_response = curl_exec($ch);
		curl_close($ch);

		$xml = simplexml_load_string($xml_response);
		$response = $xml->returncode;
		if ($response == 'SUCCESS') {
			$recordings = $xml->recordings;
		} else {
			$recordings = [];
		}
		
		$data['response'] = $response;
		$data['recordings'] = $recordings;

		$data['coaching_id'] 	= $coaching_id;
		$data['class_id'] 		= $class_id;
		$data['class'] 			= $class;
		$data['meeting_id'] 	= $meeting_id;
		$data['page_title'] 	= 'Recordings';
		$data['bc'] = array('Classrooms'=>'student/virtual_class/index/'.$coaching_id);

        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/recordings', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);
	}

	public function end_meeting ($coaching_id=0, $class_id=0) {
		redirect ('student/virtual_class/index/'.$coaching_id);
	}

	public function is_meeting_running ($coaching_id=0, $class_id=0, $member_id=0) {
		
		$api_setting = $this->virtual_class_model->get_api_settings ('join_url');
		$join = $this->virtual_class_model->join_class ($coaching_id, $class_id, $member_id);
		$meeting_url = $join['meeting_url'];
		$api_join_url = $api_setting['join_url'];
		$join_url = $api_join_url . $meeting_url;

		$is_running = $this->virtual_class_model->is_meeting_running ($coaching_id, $class_id);

		if ( $is_running == 'true') {
			$redirect = $join_url;
			$this->output->set_content_type("application/json");
	        $this->output->set_output(json_encode(array('status'=>true, 'redirect'=>$redirect )));
		} else {
			$this->output->set_content_type("application/json");
	        $this->output->set_output(json_encode(array('status'=>false, 'message'=>'' )));
		}
	}

}