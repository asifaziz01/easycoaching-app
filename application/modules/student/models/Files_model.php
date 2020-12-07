<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files_model extends CI_Model {
	public function get_file_icon($file_type){
		$icon = '';
		switch($file_type){
			case 'application/pdf':
				$icon = 'far fa-file-pdf';
			break;
			case 'image/png':
			case 'image/jpg':
			case 'image/jpeg':
			case 'image/gif':
				$icon = 'far fa-file-image';
			break;
			case 'application/rtf':
			case 'text/plain':
				$icon = 'far fa-file-alt';
			break;
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
			case 'application/msword':
			$icon = 'far fa-file-word';
			break;
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.template':
			case 'application/vnd.ms-excel':
				$icon = 'far fa-file-excel';
			break;
			case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			case 'application/vnd.openxmlformats-officedocument.presentationml.template':
			case 'application/vnd.ms-powerpoint':
				$icon = 'far fa-file-powerpoint';
			break;
			case 'application/x-freearc':
			case 'application/x-bzip':
			case 'application/x-bzip2':
			case 'application/gzip':
			case 'application/java-archive':
			case 'application/vnd.rar':
			case 'application/x-tar':
			case 'application/zip':
			case 'application/x-7z-compressed':
				$icon = 'far fa-file-archive';
			break;
			default:
				$icon = 'far fa-file';
		}
		return $icon;
	}
	public function formatSizeUnits($bytes, $suffix=true){
        if ($bytes >= 1073741824){
        	if($suffix){
        		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
        	}else{
        		$bytes = number_format($bytes / 1073741824, 2);
        	}
        }elseif ($bytes >= 1048576){
        	if($suffix){
        		$bytes = number_format($bytes / 1048576, 2) . ' MB';
        	}else{
        		$bytes = number_format($bytes / 1048576, 2);
        	}
        }elseif ($bytes >= 1024){
        	if($suffix){
        		$bytes = number_format($bytes / 1024, 2) . ' KB';
        	}else{
        		$bytes = number_format($bytes / 1024, 2);
        	}
        }elseif ($bytes > 1){
            if($suffix){
        		$bytes = $bytes . ' bytes';
        	}else{
        		$bytes = $bytes;
        	}
        }elseif ($bytes == 1){
            if($suffix){
        		$bytes = $bytes . ' byte';
        	}else{
        		$bytes = $bytes;
        	}
        }else{
            if($suffix){
        		$bytes = '0 bytes';
        	}else{
        		$bytes = 0;
        	}
        }
        return $bytes;
	}
	public function save_uploaded_file($data){
		$this->db->insert ('file_manager', $data);
		$file_id = $this->db->insert_id ();
		if  ($file_id > 0 ) {
			return "File uploaded successfully.";
		} else { 
			return false;
		}
	}
	public function rename_uploaded_file(){
		$this->db->set ('filename', $this->input->post('new_file_name'));
		$this->db->where ('id', $this->input->post('file_id'));
		$this->db->update ('file_manager');
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	public function delete_uploaded_file(){
		$this->db->where ("id", $this->input->post('file_id'));
		$this->db->delete ('file_manager');
		if($this->db->affected_rows() > 0){
			if ($this->share_count($this->input->post('file_id')) > 0){
				$this->db->where ("file_id", $this->input->post('file_id'));
				$this->db->delete ('file_sharing');
				if($this->db->affected_rows() > 0){
					return true;
				}else{
					return false;
				}
			} else { 
				return true;
			}
		}else{
			return false;
		}
	}
	public function get_used_storage($coaching_id, $member_id){
		$this->db->select_sum('size');
		$this->db->where("coaching_id=$coaching_id");
		$this->db->where("uploaded_by=$member_id");
		$sql = $this->db->get('file_manager');
		$result = $sql->row_array();
		//print_r($this->db->last_query());
		return $result['size'];
	}
	public function get_file($file_id){
		$this->db->select ('*');
		$this->db->from ('file_manager');
		$this->db->where("id=$file_id");
		$sql = $this->db->get();
		return $sql->row_array();
	}
	public function get_uploaded_file($file_id, $member_id){
		$this->db->select ('*');
		$this->db->from ('file_manager');
		$this->db->where("id=$file_id");
		$this->db->where("uploaded_by=$member_id");
		$sql = $this->db->get();
		return $sql->row_array();
	}
	public function get_file_path($file_id){
		$this->db->select ('`path`, `filename`');
		$this->db->from ('file_manager');
		$this->db->where("id=$file_id");
		$sql = $this->db->get();
		$result = $sql->row_array();
		return implode('', $result);
	}
	public function get_file_name($file_id){
		$this->db->select ('`filename`');
		$this->db->from ('file_manager');
		$this->db->where("id=$file_id");
		$sql = $this->db->get();
		$result = $sql->row_array();
		return $result['filename'];
	}
	public function is_owner($member_id, $file_id){
		$this->db->select ('id');
		$this->db->from ('file_manager');
		$this->db->where("id=$file_id");
		$this->db->where("uploaded_by=$member_id");
		$sql = $this->db->get();
		if ($sql->num_rows () > 0){
			return true;
		} else { 
			return false;
		}
	}
	public function has_access($member_id, $file_id){
		if($this->is_owner($member_id, $file_id)){
			return true;
		}else{
			$this->db->select ('`shared_with`');
			$this->db->from ('file_sharing');
			$this->db->where("file_id=$file_id");
			$this->db->where("shared_with=$member_id");
			$sql = $this->db->get();
			if ($sql->num_rows () > 0){
				return true;
			} else { 
				return false;
			}
		}
	}
	public function get_uploaded_files($coaching_id, $member_id){
		$this->db->select ('*');
		$this->db->from ('file_manager');
		$this->db->where("coaching_id=$coaching_id");
		$this->db->where("uploaded_by=$member_id");
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->result_array ();
			foreach ($result as $i => $row) {
				$result[$i]['share_count'] = $this->share_count($row['id']);
			}
			$files_data = array(
				'result' => $result,
				'total' => $sql->num_rows()
			);
			return $files_data;
		} else { 
			return false;
		}
	}
	public function get_shared_files($member_id){
		$this->db->select ('*');
		$this->db->from ('file_sharing');
		$this->db->where("shared_with=$member_id");
		$sql = $this->db->get ();
		$result = $sql->result_array();

		$sharedFiles = array();
		$result = $sql->result_array();
		foreach ($result as $i => $row) {
			$row_file_id = $row['file_id'];
			unset($row['file_id']);
			$sharedFiles[$i] = array_merge($row, $this->get_file($row_file_id));
			//$sharedFiles[$i]['share_count'] = $this->share_count($row_file_id);
		}
		return $sharedFiles;
	}
	public function not_shared_users($coaching_id, $file_id){
		$prefix = $this->db->dbprefix;
  		$query = "SELECT * FROM `".$prefix."members` WHERE `coaching_id` = $coaching_id AND `member_id` NOT IN(SELECT `shared_with` as `shared_users` FROM `".$prefix."file_sharing` WHERE `file_id` = $file_id)
		   ";
		$sql = $this->db->query($query);
		return $sql->result_array();
	}
	public function shared_users($file_id){
		$this->db->select(array(
				'`shared_with` as `shared_users`'
        	)
		);
		$this->db->where("file_id=$file_id");
		$sql = $this->db->get('file_sharing');
		$sharedUsers = array();
		$result = $sql->result_array();
		foreach ($result as $row) {
			$sharedUsers[] = $row['shared_users'];
		}
		return $sharedUsers;
	}
	public function share_count($file_id){
		$this->db->select(array(
				'COUNT(`share_id`) as `share_count`'
        	)
		);
		$this->db->where("file_id=$file_id");
		$sql = $this->db->get('file_sharing');
		$result = $sql->row_array();
		return $result['share_count'];
	}
	public function share_file($coaching_id, $member_id, $file_id, $members){
		$share_ids = array();
		foreach ($members as $member) {
			$data = array(
				'file_id' => $file_id,
				'shared_with' => $member,
				'shared_on' => time()
			);
			if  ($this->db->insert('file_sharing', $data)) {
				$share_ids[] = $this->db->insert_id();
			}
		}
		if(count($share_ids) === count($members)){
			return true;
		}else{
			return false;
		}
	}
}