<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Attendance extends MX_Controller {
   
	public function __construct () {
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_student'];
	    $models = ['attendance_model'];
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
		if ($coaching_id==0) {
            $coaching_id = $this->session->userdata ('coaching_id');
        }
        if ($member_id==0) {
            $member_id = $this->session->userdata ('member_id');
        }
		$this->my_attendance ($coaching_id, $member_id);
	}

	public function my_attendance ($coaching_id=0, $member_id=0, $from_dt=0, $to_dt=0) { 

		$cal_config = $this->config->item('calendar');
		$this->load->library('calendar', $cal_config);
		if ($from_dt == '') {
			$from_dt = date ('Y-m-d');
		} 
		if ($to_dt == '') {
			$to_dt = date ('Y-m-d');
		}
		
		list ($fy, $fm, $fd) = explode ('-', $from_dt);
		$from_dt_string = mktime (0, 0, 0, $fm, $fd, $fy);
	
		list ($ty, $tm, $td) = explode ('-', $to_dt);
		$to_dt_string = mktime (0, 0, 0, $tm, $td, $ty);
	
		$data['from_dt'] = $from_dt;
		$data['from_dt_string']	= $from_dt_string;
		$data['to_dt'] = $to_dt;
		$data['to_dt_string']	= $to_dt_string;		
		
		/*---==== For date-wise report ====----*/
		$attendance = $this->attendance_model->my_attendance ($coaching_id, $member_id, $from_dt_string, $to_dt_string);
		$absent = 0;
		$present = 0;
		$leave = 0;
		if (! empty ($attendance)) {
			foreach ($attendance as $att) {
				if ($att['attendance'] == ATTENDANCE_PRESENT) {
					$present++;
				}
				if ($att['attendance'] == ATTENDANCE_ABSENT) {
					$absent++;
				}
				if ($att['attendance'] == ATTENDANCE_LEAVE) {
					$leave++;
				}
			}
		}
		$data['num_present'] = $present;
		$data['num_absent'] = $absent;
		$data['num_leave'] = $leave;
		
		/*---==== For month-wise report ====----*/
		$from_date = 1;
		$to_date = 1;
		//$attendance = $this->attendance_model->member_report ($member_id, $from_date, $to_date);

		$data['coaching_id'] = $coaching_id;
		$data['member_id'] = $member_id;
		$data['page_title']  = 'My Attendance';
		
		/*---=== Back Link ===---*/
		$data['bc'] = array ('Dashboard'=>'student/home/dashboard/'.$coaching_id);
		$data['script'] = $this->load->view ('attn/scripts/my_attendance', $data, true);
			
		$this->load->view ( INCLUDE_PATH  . 'header', $data);
		$this->load->view ( 'attn/my_attendance', $data);
		$this->load->view ( INCLUDE_PATH  . 'footer', $data);	
	}
}