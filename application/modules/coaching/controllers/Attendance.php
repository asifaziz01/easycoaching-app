<?php if (! defined('BASEPATH')) { exit ('No direct script access allowed'); }

class Attendance extends MX_Controller {

    public function __construct ()  {
        // Load Config and Model files required throughout Users sub-module
        $config = ['config_coaching'];
        $models = ['attendance_model', 'users_model', 'subscription_model'];
        $this->common_model->autoload_resources($config, $models);
    
        $cid = $this->uri->segment (4);
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
            if ($today > $coaching['ending_on']) {
                $this->message->set ('Your subscription has expired. Choose a plan to upgrade', 'danger', true);
                redirect ('coaching/subscription/browse_plans/'.$cid.'/'.$current_plan);
            }
        }        
    }


    public function index($coaching_id=0, $role_id=0, $status='-1', $batch_id=0, $date='')
    {
        if ($date == '') {
            $date = date('Y-m-d');
        }
        list($y, $m, $d) = explode('-', $date);
        $dt_string = mktime(0, 0, 0, $m, $d, $y);
        $data['date'] = $date;
        $data['dt_string']	= $dt_string;
        $role_lvl 		 	= $this->session->userdata('role_lvl');
        $admin 				= false;
        $data['results'] 	= $this->users_model->get_users($coaching_id, $role_id, $status);
        $data['roles']	 	=  $this->users_model->get_user_roles($admin, $role_lvl);
        $data['user_status']= $this->common_model->get_sys_parameters(SYS_USER_STATUS);
        $data['coaching_id']= $coaching_id;
        $data['role_id'] 	= $role_id;
        $data['status'] 	= $status;
        $data['batch_id'] 	= $batch_id;
        $data['page_title'] = 'Attendance';
        $data['sub_title']  = 'Take Attendance';
        // Get attendance
        $attendance = [];
        if (! empty($data['results'])) {
            foreach ($data['results'] as $row) {
                $id = $row['member_id'];
                $att = $this->attendance_model->member_attendance($id, $dt_string);
                $attendance[$id] = $att;
            }
        }
        $data['attendance']	= $attendance;
        if (! empty($data['results'])) {
            $data['num_results'] = count($data['results']);
        } else {
            $data['num_results'] = 0;
        }
        $data['bc'] = array('Dashboard'=>'coaching/home/dashboard/'.$coaching_id);
        $data['script'] = $this->load->view('attendance/scripts/index', $data, true);
        $this->load->view(INCLUDE_PATH . 'header', $data);
        $this->load->view('attendance/index', $data);
        $this->load->view(INCLUDE_PATH . 'footer', $data);
    }
}
