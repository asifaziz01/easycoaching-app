<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Notification_model extends CI_Model {
	public function get_all_subscriptions() {
		$sql = $this->db->get('notifications');
		return $sql->result_array();
	}
	public function get_coaching_members_subscriptions($coaching_id = 0, $role_id = 0) {
		$this->db->select('member_id');
		$this->db->from('pwa_members');
		$this->db->where('coaching_id', $coaching_id);
		if ($role_id !== 0) {
			$this->db->where('role_id', $role_id);
		}
		$sub_query = $this->db->get_compiled_select();

		$this->db->where("member_id IN ($sub_query)");
		$sql = $this->db->get('notifications');
		return $sql->result_array();
	}
	public function get_subscription($member_id) {
		$this->db->where('member_id', $member_id);
		$sql = $this->db->get('notifications');
		if ($sql->num_rows() > 0) {
			$result = $sql->row_array();
			return $result;
		} else {
			return false;
		}
	}
	public function save_subscription($member_id, $subscribeData, $status = 1) {
		$member_id = intval($member_id);
		$this->db->where('member_id', $member_id);
		$query = $this->db->get("notifications");
		if ($query->num_rows() > 0) {
			$subscription = $query->row_array();
			$this->db->trans_start();
			$this->db->where('id', $subscription['id']);
			$this->db->where('member_id', $member_id);
			$this->db->update("notifications", array(
				'status' => $status,
				'endpoint' => $subscribeData['endpoint'],
				'expiration_time' => NULL,
				'auth' => $subscribeData['keys']['auth'],
				'p256dh' => $subscribeData['keys']['p256dh'],
			));
			$this->db->trans_complete();
			if ($this->db->trans_status() === true) {
				return true;
			} else {
				return false;
			}
		} else {
			$data = array(
				'member_id' => $member_id,
				'status' => $status,
				'endpoint' => $subscribeData['endpoint'],
				'expiration_time' => NULL,
				'auth' => $subscribeData['keys']['auth'],
				'p256dh' => $subscribeData['keys']['p256dh'],
			);
			// echo $this->db->last_query();
			$this->db->insert('notifications', $data);
			if ($this->db->affected_rows() > 0) {
				// $this->db->insert_id();
				return true;
			} else {
				return false;
			}
		}
	}
}