<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends MX_Controller {
    
    public function __construct () {
		$config = ['login/config_login'];
	    $models = ['coaching/coaching_model'];	    
	    $this->common_model->autoload_resources ($config, $models);
	    $this->load->helper ('file');
	}

	public function create_coaching () {
		$data['page_title'] = 'Coaching Sign-Up';
		$data['logo'] = base_url ($this->config->item('system_logo'));
		$data['coaching'] = false;
		$data['script'] = $this->load->view ('scripts/create_coaching', $data, true);
		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('create_coaching', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);
	}

	public function update_tokens () {
		$this->load->helper ('string');

		$i = 0;
		$sql = $this->db->get ('members');
		foreach ($sql->result_array () as $row) {
			$adm_no = $row['adm_no'];
			$member_id = $row['member_id'];
			$coaching_id = $row['coaching_id'];
			$salt = random_string ('alnum', 4);
			$str = $adm_no . $coaching_id . $member_id . $salt;
			$user_token = md5($str);

			$this->db->set ('user_token', $user_token);
			$this->db->where ('member_id', $member_id);
			$q = $this->db->update ('members');

			$i++;
		}

		echo $i;
	}
}