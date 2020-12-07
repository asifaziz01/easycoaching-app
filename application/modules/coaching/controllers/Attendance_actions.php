<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Attendance_actions extends MX_Controller
{
    public function __construct()
    {
        // Load Config and Model files required throughout Users sub-module
        $config = [ 'config_coaching'];
        $models = ['attendance_model', 'coaching_model', 'users_model'];
        $this->common_model->autoload_resources($config, $models);
    }


    /* LIST USERS
        Function to list all or selected users
    */
    
    public function search_users()
    {
        $data = $this->attendance_model->search_users();
        $this->output->set_content_type("application/json");
        $this->output->set_output(json_encode(array('status'=>true, 'data'=>$data)));
    }
    public function get_attendance($coaching_id=0, $date='')
    {
    	list($y, $m, $d) = explode('-', $date);
        $dt_string = mktime(0, 0, 0, $m, $d, $y);
        $results = $this->users_model->get_users($coaching_id, 0, 1);
        $attendance = [];
        if (! empty($results)) {
            foreach ($results as $row) {
                $id = $row['member_id'];
                $att = $this->attendance_model->member_attendance($id, $dt_string);
                $attendance[$id] = $att;
            }
        }
        $this->output->set_content_type("application/json");
        $this->output->set_output(
        	json_encode(
        		array(
        			'status'=>true,
        			'date'=> $dt_string,
        			'attendance' => $attendance
        		)
        	)
        );
    }

    public function mark_attendance($coaching_id=0, $member_id=0, $status=0, $date=0) {
        $data = $this->attendance_model->mark_attendance ($coaching_id, $member_id, $status, $date);
        $this->output->set_content_type("application/json");
        $this->output->set_output(json_encode(array('status'=>true, 'message'=>'Attendance marked', 'type'=>$status)));
    }
}
