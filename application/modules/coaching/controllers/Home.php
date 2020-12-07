<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Home extends MX_Controller {
	
	public function __construct () { 
	    $config = ['config_coaching', 'config_virtual_class'];
	    $models = ['coaching_model', 'users_model', 'subscription_model', 'virtual_class_model'];
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
 
	public function dashboard ($coaching_id=0, $member_id=0) {
		
		 if ($coaching_id==0) {
            $coaching_id = $this->session->userdata ('coaching_id');
        }
        if ($member_id==0){
            $member_id = $this->session->userdata ('member_id');
        }
        $role_id = $this->session->userdata ('role_id');

        if ($role_id == USER_ROLE_TEACHER) {
        	redirect ('coaching/home/teacher/'.$coaching_id.'/'.$member_id);
        }

        $data['coaching_id'] = $coaching_id;
        $data['member_id'] = $member_id;


        if ($role_id == USER_ROLE_SUPER_ADMIN || $role_id == USER_ROLE_ADMIN) {
        	$role = USER_ROLE_COACHING_ADMIN;
        } else {
        	$role = $role_id;
        }

		$data['dashboard_menu'] = $this->common_model->load_acl_menus ($role, 0, MENUTYPE_DASHBOARD);

		$data['coaching'] = $this->coaching_model->get_coaching ($coaching_id);
		$data['subscription'] = $this->subscription_model->get_coaching_subscription ($coaching_id);
		$data['announcements'] = $this->coaching_model->get_coaching_announcements ($coaching_id);
		
		$start_date = time ();
		// seven days from today
		$num_days = 7 * 24 * 3600;
		$end_date = $start_date - $num_days;
		$data['user_registration'] = $this->users_model->user_registration_between ($coaching_id, $start_date, $end_date);

		// Virtual Class
		$data['virtual_class'] = $this->virtual_class_model->get_all_classes ($coaching_id);
		if (! empty($data['virtual_class'])) {
			$num_vc = count($data['virtual_class']);
		} else {
			$num_vc = 0;
		}
		$data['num_vc'] = $num_vc;
		
		$data['tests'] = $this->coaching_model->get_coaching_tests ($coaching_id);

		// Users
		$users['total'] = $this->coaching_model->num_users ($coaching_id);
		$users['num_teachers'] = $this->coaching_model->num_users ($coaching_id, USER_ROLE_TEACHER);
		$users['num_students'] = $this->coaching_model->num_users ($coaching_id, USER_ROLE_STUDENT);
		$users['num_active'] = $this->coaching_model->num_users ($coaching_id, 0, USER_STATUS_ENABLED);
		$users['num_disabled'] = $this->coaching_model->num_users ($coaching_id, 0, USER_STATUS_DISABLED);
		$users['num_pending'] = $this->coaching_model->num_users ($coaching_id, 0, USER_STATUS_UNCONFIRMED);

		// Tests
		$tests['total'] = $this->coaching_model->num_tests ($coaching_id);
		$tests['num_regular'] = $this->coaching_model->num_tests ($coaching_id, TEST_TYPE_REGULAR);
		$tests['num_practice'] = $this->coaching_model->num_tests ($coaching_id, TEST_TYPE_PRACTICE);
		$tests['num_published'] = $this->coaching_model->num_tests ($coaching_id, 0, TEST_STATUS_PUBLISHED);
		$tests['num_unpublished'] = $this->coaching_model->num_tests ($coaching_id, 0, TEST_STATUS_UNPUBLISHED);

		$data['users'] = $users;
		$data['tests'] = $tests;
		$data['page_title'] = 'Dashboard';

        //$data['bc'] = array ('Coachings'=>'admin/coaching/index');
		
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ('home/dashboard', $data);
		$this->load->view (INCLUDE_PATH . 'footer', $data);		
		
	}


	public function teacher ($coaching_id=0, $member_id=0) {
		
		if ($coaching_id==0) {
            $coaching_id = $this->session->userdata ('coaching_id');
        }
        if ($member_id==0){
            $member_id = $this->session->userdata ('member_id');
        }
        $role_id = $this->session->userdata ('role_id');

        $data['coaching_id'] = $coaching_id;
        $data['member_id'] = $member_id;

        if ($role_id == USER_ROLE_SUPER_ADMIN || $role_id == USER_ROLE_ADMIN) {
        	$role = USER_ROLE_COACHING_ADMIN;
        } else {
        	$role = $role_id;
        }

		$data['my_classrooms'] = $this->virtual_class_model->my_classroom ($coaching_id, $member_id);
		if (! empty($data['my_classroom'])) {
			$num_vc = count($data['my_classroom']);
		} else {
			$num_vc = 0;
		}
		$data['num_vc'] = $num_vc;
		
		$data['tests'] = $this->coaching_model->get_coaching_tests ($coaching_id);

		$data['dashboard_menu'] = $this->common_model->load_acl_menus ($role, 0, MENUTYPE_DASHBOARD);

		$data['coaching'] = $this->coaching_model->get_coaching ($coaching_id);
		$data['announcements'] = $this->coaching_model->get_coaching_announcements ($coaching_id);

		// Tests
		$tests['total'] = $this->coaching_model->num_tests ($coaching_id);
		$tests['num_regular'] = $this->coaching_model->num_tests ($coaching_id, TEST_TYPE_REGULAR);
		$tests['num_practice'] = $this->coaching_model->num_tests ($coaching_id, TEST_TYPE_PRACTICE);
		$tests['num_published'] = $this->coaching_model->num_tests ($coaching_id, 0, TEST_STATUS_PUBLISHED);
		$tests['num_unpublished'] = $this->coaching_model->num_tests ($coaching_id, 0, TEST_STATUS_UNPUBLISHED);

		$data['tests'] = $tests;

		$data['page_title'] = 'Dashboard';
		
		$this->load->view (INCLUDE_PATH . 'header', $data);
		$this->load->view ('home/teacher', $data);
		$this->load->view (INCLUDE_PATH . 'footer', $data);		
		
	}
	

}