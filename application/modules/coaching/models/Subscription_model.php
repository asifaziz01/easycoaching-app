<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscription_model extends CI_Model {

	public function get_coaching_subscription ($coaching_id=0) {
		$this->db->select ('C.*, CS.id AS subscription_id, CS.starting_from, CS.ending_on, CS.created_by, SP.*, SP.id AS sp_id');
		$this->db->from ('coachings C, coaching_subscriptions CS, subscription_plans SP');
		$this->db->where('CS.coaching_id=C.id');
		$this->db->where('SP.id=CS.plan_id');
		$this->db->where ('CS.id', $coaching_id);
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else { 
			return false;
		}
	}

	/* Subscription Plans */
	public function subscription_plans ($status='', $paid_plans=0) {
		if ($status != '') {
			$this->db->where ('status', $status);
		}
		if ($paid_plans == 1) {
			$this->db->where ('price > ', 0);
		}
		$this->db->order_by ('ordering', 'ASC');
		$sql = $this->db->get ('subscription_plans');
		return $sql->result_array ();
	}

	/* Subscription Plans */ 
	public function change_plan ($coaching_id=0, $plan_id=0) {
		$created_by = intval ($this->session->userdata ('member_id'));
		$this->db->set ('plan_id', $plan_id);
		$this->db->set ('created_by', $created_by);
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->update ('coaching_subscriptions');
	}

	/* Subscription Plan */
	public function subscription_plan ($plan_id=0) {
		$this->db->where ('id', $plan_id);
		$sql = $this->db->get ('subscription_plans');
		return $sql->row_array ();
	}

}