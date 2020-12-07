<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriptions_model extends CI_Model {
	
	/* Subscription Plans */
	public function subscription_plans ($status='') {
		if ($status != '') {
			$this->db->where ('status', $status);
		}
		$this->db->order_by ('ordering', 'ASC');
		$sql = $this->db->get ('subscription_plans');
		return $sql->result_array ();
	}

	/* Subscription Plan */
	public function subscription_plan ($plan_id=0) {
		$this->db->where ('id', $plan_id);
		$sql = $this->db->get ('subscription_plans');
		return $sql->row_array ();
	}

	/* Create/Edit Subscription Plan */
	public function create_plan ($plan_id=0) {
		$data['title'] = $this->input->post ('title');
		$data['description'] = $this->input->post ('description');
		$data['duration'] = $this->input->post ('duration');
		$data['price'] = $this->input->post ('price');
		$data['max_users'] = $this->input->post ('max_users');
		$data['status'] = $this->input->post ('status');
		
		if ($plan_id > 0) {
			$this->db->where ('id', $plan_id);
			$this->db->update ('subscription_plans', $data);
		} else {
			$this->db->insert ('subscription_plans', $data);			
		}
	}

	/* Delete Subscription Plan */
	public function delete_plan ($plan_id=0) {
		$this->db->where ('id', $plan_id);
		$sql = $this->db->delete ('subscription_plans');
	}

}