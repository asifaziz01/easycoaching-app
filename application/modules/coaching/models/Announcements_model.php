<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Announcements_model extends CI_Model {

	public function get_announcement ($coaching_id=0,$announcement_id=0) {
		$this->db->where ('announcement_id', $announcement_id);
		$this->db->where ('coaching_id', $coaching_id);
		return $this->db->get ("coaching_announcements")->row_array();
	}
	public function get_announcements ($coaching_id=0) {
		$this->db->where ('coaching_id', $coaching_id);

		return $this->db->get ("coaching_announcements")->result_array();
		
	}

	//=========== Model for Create/Edit tests =======================
	public function create_announcement ($coaching_id=0,$announcement_id=0,$member_id=0) {

		$data['title']=$this->input->post('title');
		$data['description']=$this->input->post('description');
		$data['start_date']=strtotime($this->input->post('start_date'));
		$data['end_date']=strtotime($this->input->post('end_date'));
		$data['status']=$this->input->post('status');
		$data['created_by'] = $this->session->userdata ('member_id');
		if($announcement_id > 0){
			$this->db->where('announcement_id',$announcement_id);
			$this->db->update('coaching_announcements',$data);
		}
		else{
			$data['coaching_id']=$coaching_id;
			$data['created_by'] = $this->session->userdata ('member_id');

			$query=$this->db->insert('coaching_announcements',$data);

		}
		
	}
	public function delete_announcement ($coaching_id=0,$announcement_id=0) {
		$this->db->where ('announcement_id', $announcement_id);
		$this->db->delete ('coaching_announcements');
		redirect('coaching/announcements/index/'.$coaching_id);

	}
	
}
	

	 
	