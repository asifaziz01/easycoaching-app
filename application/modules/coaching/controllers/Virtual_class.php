 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Virtual_class extends MX_Controller {	

	var $toolbar_buttons = [];

	public function __construct () {		
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_coaching', 'config_virtual_class'];
	    $models = ['virtual_class_model', 'users_model', 'subscription_model'];
	    $this->common_model->autoload_resources ($config, $models);

	    $this->load->helper ('string'); 

	    $cid = $this->uri->segment (4);
        $this->toolbar_buttons['<i class="fa fa-list"></i> All Classes']= 'coaching/virtual_class/index/'.$cid;
        if ($this->session->userdata ('role_id') <> USER_ROLE_TEACHER ) {
        	$this->toolbar_buttons['<i class="fa fa-plus-circle"></i> Create Class']= 'coaching/virtual_class/create_class/'.$cid;
        	$this->toolbar_buttons['<i class="fa fa-circle"></i> Categories']= 'coaching/virtual_class/categories/'.$cid;
        }
        
        if ($this->session->userdata ('is_admin') == TRUE) {
        } else {
        	// Security step to prevent unauthorized access through url
            if ($cid == true && $this->session->userdata ('coaching_id') <> $cid) {
                $this->message->set ('Direct url access not allowed', 'danger', true);
                redirect ('coaching/home/dashboard');
            }

        	// Check subscription plan expiry
            $coaching = $this->subscription_model->get_coaching_subscription ($cid);
            $today = time ();
            $current_plan = $coaching['subscription_id'];
            if ($today > $coaching['ending_on'] && $this->session->userdata ('role_id') != USER_ROLE_STUDENT) {
            	$this->message->set ('Your subscription has expired. Choose a plan to upgrade', 'danger', true);
            	redirect ('coaching/subscription/browse_plans/'.$cid.'/'.$current_plan);
            }
        }
	}

	// Categories
	public function categories ($coaching_id=0) {
		/* Breadcrumbs */ 
		$data['bc'] = array ('Dashboard'=>'coaching/virtual_class/index/'.$coaching_id);

		$data['page_title'] = 'Categories';
		$data['coaching_id'] = $coaching_id;
		
		/* --==// Toolbar //==-- */
		$data['toolbar_buttons'] = $this->toolbar_buttons;

		$data['categories'] = $this->virtual_class_model->get_categories ($coaching_id);

		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('virtual_class/categories', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);	    
	}

	public function index ($coaching_id=0, $category_id='-1') {
		
		$data['coaching_id'] = $coaching_id;
		$data['category_id'] = $category_id;
		$member_id = $this->session->userdata ('member_id');
		$data['bc'] = array('Dashboard'=>'coaching/home/dashboard/'.$coaching_id);
		$data['toolbar_buttons'] = $this->toolbar_buttons;
		if ($this->session->userdata ('role_id') == USER_ROLE_TEACHER) {
			$data['class'] = $this->virtual_class_model->my_classroom ($coaching_id, $member_id, $category_id);
		} else {
			$data['class'] = $this->virtual_class_model->get_all_classes ($coaching_id, $category_id);
		}

		$data['categories'] = $this->virtual_class_model->get_categories ($coaching_id);

        $data['script'] = $this->load->view('virtual_class/scripts/index', $data, true);
        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/index', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);		
	}

	public function create_class ($coaching_id=0, $class_id=0, $category_id=0) {
		
        if ($this->session->userdata ('role_id') == USER_ROLE_TEACHER ) {
        	$this->message->set ('Not allowed', 'danger', true);
        	redirect ('coaching/virtual_class/index/'.$coaching_id.'/'.$class_id);
        }

		$data['coaching_id'] = $coaching_id;
		$data['class_id'] = $class_id;
		$data['page_title'] = 'Create Classroom';
		$data['bc'] = array('Virtual Class'=>'coaching/virtual_class/index/'.$coaching_id);

		$data['class'] = $this->virtual_class_model->get_class ($coaching_id, $class_id);
		$data['categories'] = $this->virtual_class_model->get_categories ($coaching_id);
		$data['attendee_pwd'] = random_string ('numeric', 4);
		$data['moderator_pwd'] = random_string ('numeric', 4);

		if ($class_id > 0) {
			$data['category_id'] = $data['class']['category_id'];
		} else {
			$data['category_id'] = $category_id;
		}

        $data['script'] = $this->load->view('virtual_class/scripts/create_class', $data, true);
        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/create_class', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);		
	}

	public function add_participants ($coaching_id=0, $class_id=0, $role_id=USER_ROLE_STUDENT, $batch_id=0) {
		
		$roles = [USER_ROLE_COACHING_ADMIN, USER_ROLE_TEACHER, USER_ROLE_STUDENT];

		if ($role_id <> USER_ROLE_STUDENT) {
			$batch_id = 0;
		}

		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$data['class'] = $this->virtual_class_model->get_class ($coaching_id, $class_id);
		$data['users'] = $this->users_model->get_users ($coaching_id, $role_id, USER_STATUS_ENABLED, $batch_id);
		$data['roles'] = $this->users_model->get_user_roles (0, 0, $roles);
		$data['batches'] = $this->users_model->get_batches ($coaching_id);
		$participants = $this->virtual_class_model->get_participants ($coaching_id, $class_id);
		if (! empty($participants)) {
			$num_participants = count($participants);
		} else {
			$num_participants = 0;
		}

		$data['coaching_id'] = $coaching_id;
		$data['class_id'] = $class_id;
		$data['role_id'] = $role_id;
		$data['batch_id'] = $batch_id;
		$data['num_participants'] = $num_participants;
		$data['page_title'] = 'Add Participants';
		$data['bc'] = array('Participants'=>'coaching/virtual_class/participants/'.$coaching_id.'/'.$class_id);

        $data['script'] = $this->load->view ('virtual_class/scripts/add_participants', $data, true);
        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/add_participants', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);		
	}

	public function participants ($coaching_id=0, $class_id=0) {		

		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$data['class'] = $this->virtual_class_model->get_class ($coaching_id, $class_id);
		$data['participants'] = $this->virtual_class_model->get_participants ($coaching_id, $class_id);
		if (! empty($data['participants'])) {
			$num_participants = count($data['participants']);
		} else {
			$num_participants = 0;
		}

		$data['num_participants'] = $num_participants;
		$data['coaching_id'] = $coaching_id;
		$data['class_id'] = $class_id;
		$data['page_title'] = 'Participants';
		$data['bc'] = array('Dashboard'=>'coaching/virtual_class/index/'.$coaching_id);

        //$data['script'] = $this->load->view('attendance/scripts/index', $data, true);
        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/participants', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);		
	}


	public function class_details ($coaching_id=0, $class_id=0) {		

		$data['coaching_id'] = $coaching_id;
		$data['class_id'] = $class_id;
		$data['page_title'] = 'Virtual Classroom';
		$data['bc'] = array('Dashboard'=>'coaching/virtual_class/index/'.$coaching_id);

		$member_id = $this->session->userdata ('member_id');
		$user = $this->users_model->get_user ($member_id);
		$fullName = $user['first_name'];
		$class = $this->virtual_class_model->get_class ($coaching_id, $class_id);

		$api_setting = $this->virtual_class_model->get_api_settings ();
		$shared_secret = $api_setting['shared_secret'];

		$call_name = 'join';

		$query_string = '';
		$query_string .= 'fullName='.$fullName;
		$query_string .= '&meetingID='.$class['meeting_id'];
		$query_string .= '&password='.$class['moderator_pwd'];
		$query_string .= '&userID='.$user['adm_no'];

		$final_string = $call_name . $query_string . $shared_secret;

		$checksum = sha1 ($final_string);

		$meeting_url = '';
		$meeting_url .= $api_setting['api_url'];
		$meeting_url .= $call_name;
		$meeting_url .= '?';
		$meeting_url .= $query_string;
		$meeting_url .= '&checksum='.$checksum;

		$data['class'] = $class;
		$data['meeting_url'] = $meeting_url;
		$data['api_setting'] = $api_setting;

        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/class_details', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);		
	}

	public function recordings ($coaching_id=0, $class_id=0, $meeting_id=0) {		

		$api_setting = $this->virtual_class_model->get_api_settings ();
		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$class = $this->virtual_class_model->get_class ($coaching_id, $class_id);

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
		$data['bc'] = array('Classrooms'=>'coaching/virtual_class/index/'.$coaching_id);

        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/recordings', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);
	}
		

	public function join_class ($coaching_id=0, $class_id=0, $member_id=0) {
		
		$api_setting = $this->virtual_class_model->get_api_settings ('join_url');
		$class = $this->virtual_class_model->get_class ($coaching_id, $class_id);
		$join = $this->virtual_class_model->join_class ($coaching_id, $class_id, $member_id);

		$error_code = $this->config->item ('vc_error_code');

		if ($join) {
			// User is a participant in this classroom
			$meeting_url = $join['meeting_url'];
			$api_join_url = $api_setting['join_url'];
			$join_url = $api_join_url . $meeting_url;

			$is_running = $this->virtual_class_model->is_meeting_running ($coaching_id, $class_id);

			if ( $is_running == 'true') {
				// if class has already started, join class
				redirect ($join_url);
			} else {
				// if class has not started, create class and join (if moderator of class)
				if ($join['role'] == VM_PARTICIPANT_MODERATOR)  {
					$response = $this->virtual_class_model->create_meeting ($coaching_id, $class_id);
					if ($response == 'SUCCESS') {
						redirect ($join_url);
					} else {
						$error = $error_code[1];
					}
				} else {
					$error = $error_code[2];
				}
			}
		} else {
			$error = $error_code[3];
		}
		
		$data['coaching_id'] = $coaching_id;
		$data['class_id'] = $class_id;
		$data['page_title'] = 'Classroom Error';
		$data['bc'] = array('Virtual Classroom'=>'coaching/virtual_class/index/'.$coaching_id);
		
		$data['error'] = $error;

        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('virtual_class/error', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);		
	}

	public function end_meeting ($coaching_id=0, $class_id=0) {
		$this->virtual_class_model->add_to_history ($coaching_id, $class_id);
		if ($this->session->userdata ('role_id') == USER_ROLE_STUDENT) {
			redirect ('student/virtual_class/index/'.$coaching_id);
		} else {
			redirect ('coaching/virtual_class/index/'.$coaching_id);
		}
	}
}