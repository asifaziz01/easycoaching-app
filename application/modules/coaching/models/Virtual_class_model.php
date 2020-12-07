<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Virtual_class_model extends CI_Model {

	/* VC Categories */
	public function get_categories ($coaching_id=0, $status='-1') {
		if ($status > '-1') {
			$this->db->where ('status', $status);
		}
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('virtual_class_categories');
		return $sql->result_array ();
	}

	public function get_category ($category_id=0) {
		$this->db->where ('id', $category_id);
		$sql = $this->db->get ('virtual_class_categories');
		return $sql->row_array ();
	}

	public function create_category ($coaching_id=0, $category_id=0) {

		$data['title'] 				= $this->input->post ('title');
		
		if ($category_id > 0 ) {
			$this->db->where ('coaching_id', $coaching_id);
			$this->db->where ('id', $category_id);
			$this->db->update ('virtual_class_categories', $data);
		} else {
			$data['status'] 		= 1;
			$data['coaching_id'] 	= $coaching_id;
			$data['creation_date'] = time ();
			$data['created_by'] = $this->session->userdata ('member_id');
			$this->db->insert ('virtual_class_categories', $data);
			$category_id = $this->db->insert_id ();
		}		
		return $category_id;		
	}

	// Add ITS Categories to a plan
	public function remove_category ($coaching_id=0, $category_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('id', $category_id);
		$sql = $this->db->delete ('virtual_class_categories');

		$this->db->set ('category_id', 0);
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('category_id', $category_id);
		$sql = $this->db->update ('virtual_classroom');
	}

	/*--------------- // Category // ----------------------*/
	public function get_all_classes ($coaching_id=0, $category_id='-1') {
		$result = [];
		$this->db->select ('VC.*');
		$this->db->from ('virtual_classroom VC');
		$this->db->where ('VC.coaching_id', $coaching_id);
		if ($category_id > '-1') {
			$this->db->where ('VC.category_id', $category_id);
		}
		$sql = $this->db->get ();
		foreach ($sql->result_array () as $row) {
			$row['running'] = $this->is_meeting_running ($coaching_id, $row['class_id']);
			$participants = $this->get_participants ($coaching_id, $row['class_id']);
			if (! empty($participants)) {
				$num_participants = count($participants);
			} else {
				$num_participants = 0;
			}
			$row['num_participants'] = $num_participants;
			$result[] = $row;
		}
		return $result;
	}

	public function get_class ($coaching_id=0, $class_id=0) {
		$this->db->select ('VC.*');
		$this->db->from ('virtual_classroom VC');
		//$this->db->join ('virtual_class_categories VCC', 'VC.category_id=VCC.id');
		$this->db->where ('VC.coaching_id', $coaching_id);
		$this->db->where ('VC.class_id', $class_id);
		$sql = $this->db->get ();
		return $sql->row_array ();
	}

	public function create_classroom ($coaching_id=0, $class_id=0) {		

		// 1. Basic settings
		$class_name = $this->input->post ('class_name');
		$category = $this->input->post ('category');
		$description = $this->input->post ('description');
		$meeting_id = $this->get_meeting_id ($coaching_id, $class_id);
		$pwd = $this->get_password ($coaching_id, $class_id);
		$attendee_pwd = $pwd['attendee_pwd'];
		$moderator_pwd = $pwd['moderator_pwd'];
		$max_participants = VC_MAX_PARTICIPANTS;
		$duration = VC_DURATION;

		if ($this->input->post ('welcome_message')) {
			$welcome_message = $this->input->post ('welcome_message');
		} else {
			$welcome_message = VC_WELCOME_MESSAGE;
		}		
		$welcome_message = str_replace(' ', '+', $welcome_message);		
		if ($this->session->userdata ('site_title')) {
			$bannerText = $this->session->userdata ('site_title');
		} else {
			$bannerText = VC_BANNER_TEXT;
		}
		$bannerText = str_replace(' ', '+', $bannerText);

		$logoutURL = VC_LOGOUT_URL . '/' . $coaching_id . '/' . $class_id;


		// 2. Restrictions
		$wait_for_moderator = 'true';
		
		if ($this->input->post ('webcam_for_moderator')) {
			$webcam_for_moderator = 'true';
		} else {
			$webcam_for_moderator = 'false';
		}

		if ($this->input->post ('mute_mic')) {
			$mute_mic = 'true';
		} else {
			$mute_mic = 'false';
		}

		if ($this->input->post ('lock_mic')) {
			$lock_mic = 'true';
		} else {
			$lock_mic = 'false';
		}
		$allow_mods_to_unmute_users = 1;


		if ($this->input->post ('record_class')) {
			$record_class = 'true';
		} else {
			$record_class = 'false';
		}
		
		if ($this->input->post ('auto_record')) {
			$auto_record = 'true';
		} else {
			$auto_record = 'false';
		}

		if ($this->input->post ('recording_for_students')) {
			$recording_for_students = 'true';
		} else {
			$recording_for_students = 'false';
		}

		if ($this->input->post ('lock_public_chat')) {
			$lock_public_chat = 'true';
		} else {
			$lock_public_chat = 'false';
		}

		if ($this->input->post ('lock_private_chat')) {
			$lock_private_chat = 'true';
		} else {
			$lock_private_chat = 'false';
		}

		if ($this->input->post ('lock_notes')) {
			$lock_notes = 'true';
		} else {
			$lock_notes = 'false';
		}


		// 3. Schedule
		if ($this->input->post ('start_date_check') == 1) {
			$start_date = $this->input->post ('start_date');
			list ($sy, $sm, $sd) = explode ("-", $start_date);
			$shh = $this->input->post ('start_time_hh');
			$smm = $this->input->post ('start_time_mm');
			$start_date = mktime ($shh, $smm, 0, $sm, $sd, $sy);
		} else {
			$start_date = '';
		}

		if ($this->input->post ('end_date_check') == 1) {
			$end_date = $this->input->post ('end_date');
			list ($ey, $em, $ed) = explode ("-", $end_date);
			$ehh = $this->input->post ('end_time_hh');
			$emm = $this->input->post ('end_time_mm');
			$end_date = mktime ($ehh, $emm, 0, $em, $ed, $ey);
		} else {
			$end_date = '';
		}	


		$api_setting = $this->get_api_settings ();
		$shared_secret = $api_setting['shared_secret'];

		$call_name = 'create';

		$query_string = '';
		$query_string .= 'meetingID='.$meeting_id;
		$query_string .= '&moderatorPW='.$moderator_pwd;
		$query_string .= '&attendeePW='.$attendee_pwd;
		$query_string .= '&welcome='.$welcome_message;
		$query_string .= '&duration='.$duration;
		$query_string .= '&maxParticipants='.$max_participants;
		$query_string .= '&webcamsOnlyForModerator='.$webcam_for_moderator;		
		$query_string .= '&record='.$record_class;
		$query_string .= '&autoStartRecording='.$auto_record;
		$query_string .= '&lockSettingsDisablePublicChat='.$lock_public_chat;
		$query_string .= '&lockSettingsDisablePrivateChat='.$lock_private_chat;
		$query_string .= '&lockSettingsDisableNote='.$lock_notes;
		$query_string .= '&logoutURL='.urlencode($logoutURL);
		$query_string .= '&bannerText='.$bannerText;
		$query_string .= '&muteOnStart='.$mute_mic;
		$query_string .= '&allowModsToUnmuteUsers='.$allow_mods_to_unmute_users;
		$query_string .= '&lockSettingsDisableMic='.$lock_mic;
		$query_string .= '&copyright='.VC_COPYRIGHT_TEXT;
		//$query_string .= '&logo='.VC_LOGO;

		$final_string = $call_name . $query_string . $shared_secret;
		
		$checksum = sha1 ($final_string);

		// Prepare for database
		$data['meeting_id'] 		= $meeting_id;
		$data['class_name'] 		= $class_name;
		$data['category_id'] 		= $category;
		$data['description'] 		= $description;
		$data['welcome_message']	= str_replace('+', ' ', $welcome_message);
		$data['wait_for_moderator'] = $wait_for_moderator;
		$data['webcam_for_moderator'] = $webcam_for_moderator;
		$data['record_class'] 		= $record_class;
		$data['auto_start_recording'] 		= $auto_record;
		$data['recording_for_students'] 		= $recording_for_students;
		$data['lock_public_chat'] 		= $lock_public_chat;
		$data['lock_private_chat'] 		= $lock_private_chat;
		$data['lock_notes'] 		= $lock_notes;
		$data['mute_all_mics'] 		= $mute_mic;
		$data['join_listen_only'] 	= $lock_mic;
		$data['start_date'] 		= $start_date;
		$data['end_date'] 			= $end_date;
		$data['call_name'] 			= $call_name;
		$data['query_string'] 		= $query_string;
		$data['checksum'] 			= $checksum;


		if ($class_id > 0) {
			$this->db->where ('class_id', $class_id);
			$this->db->where ('coaching_id', $coaching_id);
			$sql = $this->db->update ('virtual_classroom', $data);
		} else {
			$data['coaching_id'] 		= $coaching_id;
			$data['attendee_pwd'] 		= $attendee_pwd;
			$data['moderator_pwd'] 		= $moderator_pwd;
			$data['max_participants'] 	= $max_participants;
			$data['duration'] 			= $duration;
			$data['created_by'] 		= $this->session->userdata ('member_id');
			$data['creation_date'] 		= time ();
			$sql = $this->db->insert ('virtual_classroom', $data);
			$class_id = $this->db->insert_id ();
			$member_id = $this->session->userdata ('member_id');
			$this->add_moderator ($coaching_id, $class_id, $member_id);			
		}
		return $class_id;
	}


	public function add_moderator ($coaching_id=0, $class_id=0, $member_id=0) {
		
		$user = $this->users_model->get_user ($member_id);
		$class = $this->get_class ($coaching_id, $class_id);

		$api_setting = $this->get_api_settings ();
		$shared_secret = $api_setting['shared_secret'];
		$password = $class['moderator_pwd'];
	
		$call_name = 'join';

		$participant_role = VM_PARTICIPANT_MODERATOR;

		$fullName = $user['first_name'] . ' ' . $user['last_name'] . '(Classroom Admin)';
		$fullName = str_replace(' ', '+', $fullName);
 
		$query_string = '';
		$query_string .= 'fullName='.$fullName;
		$query_string .= '&meetingID='.$class['meeting_id'];
		$query_string .= '&password='.$password;
		$query_string .= '&userID='.$member_id;

		$final_string = $call_name . $query_string . $shared_secret;
		$checksum = sha1 ($final_string);

		$meeting_url = '';		
		$meeting_url .= $query_string;
		$meeting_url .= '&checksum='.$checksum;

		$data = [];
		$data['coaching_id'] = $coaching_id;
		$data['class_id'] = $class_id;
		$data['member_id'] = $member_id;
		$data['role'] = $participant_role;
		$data['meeting_url'] = $meeting_url;
		// Insert only when not already inserted
		$this->db->where ($data);
		$sql = $this->db->get ('virtual_classroom_participants');
		if ($sql->num_rows () == 0) {
			$this->db->insert ('virtual_classroom_participants', $data);
		}
	}

	public function add_participants ($coaching_id=0, $class_id=0) {

		$coaching = $this->coaching_model->get_coaching ($coaching_id);
		$class = $this->get_class ($coaching_id, $class_id);

		$api_setting = $this->get_api_settings ();
		$shared_secret = $api_setting['shared_secret'];
	
		$call_name = 'join';

		$participant_role = $this->input->post ('participant_role');
		if ($participant_role == VM_PARTICIPANT_MODERATOR) {
			$password = $class['moderator_pwd'];
		} else {
			$password = $class['attendee_pwd'];
		}

		$users = $this->input->post ('users');
		
		if (empty ($users)) {
			// Add creating user as moderator
			$admin_id = $this->session->userdata ('member_id');
			$admin_name = 'Classroom+Admin';
			$users[$admin_id] = $admin_name;
			$participant_role = VM_PARTICIPANT_MODERATOR;
		}

		foreach ($users as $member_id=>$fullName) {
			$query_string = '';
			$query_string .= 'fullName='.$fullName;
			$query_string .= '&meetingID='.$class['meeting_id'];
			$query_string .= '&password='.$password;
			$query_string .= '&userID='.$member_id;

			$final_string = $call_name . $query_string . $shared_secret;
			$checksum = sha1 ($final_string);
	
			$meeting_url = '';
			$meeting_url .= $query_string;
			$meeting_url .= '&checksum='.$checksum;

			$data = [];
			$data['coaching_id'] = $coaching_id;
			$data['class_id'] = $class_id;
			$data['member_id'] = $member_id;
			$data['role'] = $participant_role;
			$data['meeting_url'] = $meeting_url;
			
			// Insert only when not already inserted
			$this->db->where ($data);
			$sql = $this->db->get ('virtual_classroom_participants');
			if ($sql->num_rows () == 0) {
				$this->db->insert ('virtual_classroom_participants', $data);
			}

			// Send SMS to user
			$user = $this->users_model->get_user ($member_id);
			$contact = $user['primary_contact'];
			$data['name'] = $user['first_name'];
			$data['coaching_name'] = $coaching['coaching_name'];
			$data['class_name'] = $class['class_name'];
			$data['start_date'] = $class['start_date'];
			$message = $this->load->view (SMS_TEMPLATE . 'vc_add_participant', $data, true);
			$this->sms_model->send_sms ($contact, $message);

		}


	}


	public function remove_participants ($coaching_id=0, $class_id=0) {
		$users = $this->input->post ('users');
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('class_id', $class_id);
		$this->db->where_in ('member_id', $users);
		$this->db->delete ('virtual_classroom_participants');
	}

	public function get_participants ($coaching_id=0, $class_id=0) {
		$this->db->select ('M.*, VCP.meeting_url, VCP.role');
		$this->db->from ('members M');
		$this->db->join ('virtual_classroom_participants VCP', 'M.member_id=VCP.member_id');
		$this->db->where ('VCP.coaching_id', $coaching_id);
		$this->db->where ('VCP.class_id', $class_id);
		$sql = $this->db->get ();
		return $sql->result_array ();
	}

	public function participants_added ($coaching_id=0, $class_id=0, $member_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('class_id', $class_id);
		$this->db->where ('member_id', $member_id);
		$sql = $this->db->get ('virtual_classroom_participants');
		return $sql->num_rows ();
	}

	public function delete_class ($coaching_id=0, $class_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('class_id', $class_id);
		$sql = $this->db->delete ('virtual_classroom_participants');

		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('class_id', $class_id);
		$sql = $this->db->delete ('virtual_classroom');
	}

	public function join_class ($coaching_id=0, $class_id=0, $member_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('class_id', $class_id);
		$this->db->where ('member_id', $member_id);
		$sql = $this->db->get ('virtual_classroom_participants');
		return $sql->row_array ();
	}

	public function get_api_settings ($param_name='') {
		$output = [];
		if ($param_name != '') {
			$this->db->where ('param_name', $param_name);
		}
		$sql = $this->db->get ('bigbluebutton_settings');
		if ($param_name != '') {
			$row = $sql->row_array ();
			$output[$row['param_name']] = $row['param_val'];
		} else {			
			foreach ($sql->result_array () as $row) {
				$output[$row['param_name']] = $row['param_val'];
			}
		}
		return $output;
	}

	public function get_api_details ($call_name='') {
		$this->db->where ('call_name', $call_name);
		$sql = $this->db->get ('bigbluebutton_api');
		return $sql->row_array ();
	}

	public function get_password ($coaching_id=0, $class_id=0) {
		if ($class_id > 0) {
			$this->db->select ('attendee_pwd, moderator_pwd');
			$this->db->where ('coaching_id', $coaching_id);
			$this->db->where ('class_id', $class_id);
			$sql = $this->db->get ('virtual_classroom');
			$row = $sql->row_array();
			return $row;
		} else {
			// Meeting Id must be unique
			// Trying to create a unique string out of timestamp, member_id and a random salt
			$row['attendee_pwd'] = random_string ('numeric', 4);
			$row['moderator_pwd'] = random_string ('numeric', 4);
			return $row;
		}

	}

	public function get_meeting_id ($coaching_id=0, $class_id=0) {
		if ($class_id > 0) {
			$this->db->where ('coaching_id', $coaching_id);
			$this->db->where ('class_id', $class_id);
			$sql = $this->db->get ('virtual_classroom');
			$row = $sql->row_array();
			return $row['meeting_id'];
		} else {
			// Meeting Id must be unique
			// Trying to create a unique string out of timestamp, member_id and a random salt
			$ts = time ();
			$member_id = $this->session->userdata ('member_id');
			$salt = random_string('alnum', 6);
			$meeting_id = $salt . $ts . $member_id;
			return $meeting_id;
		}

	}

	public function is_meeting_running ($coaching_id=0, $class_id=0) {

		$api_setting = $this->virtual_class_model->get_api_settings ();
		$class = $this->virtual_class_model->get_class ($coaching_id, $class_id);
		
		$api_url = $api_setting['api_url'];
		$shared_secret = $api_setting['shared_secret'];
		$call_name = 'isMeetingRunning';
		$query_string = 'meetingID='.$class['meeting_id'];

		$checksum_string = $call_name . $query_string . $shared_secret;
		$checksum = sha1 ($checksum_string);
		$url = $api_url . $call_name . '?'. $query_string . '&checksum='.$checksum;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$xml_response = curl_exec($ch);
		curl_close($ch);
		$xml = simplexml_load_string($xml_response);
		$running = $xml->running;

		return $running;
	}

	public function create_meeting ($coaching_id=0, $class_id=0) {
		
		$api_setting = $this->get_api_settings ();

		$class = $this->get_class ($coaching_id, $class_id);
		// Create call and query
		$api_url = $api_setting['api_url'];
		$call_name = $class['call_name'];
		$query_string = $class['query_string'];
		$checksum = $class['checksum'];

		$final_string = $api_url . $call_name .'?'.  $query_string . '&checksum='.$checksum;
		
		// Upload whiteboard slide
		$post_slide = '<xml>
						<modules>
						     <module name="presentation">
						      <document url="https://easycoachingapp.com/apps/whiteboard.pdf"/>
						   </module>
						</modules>
					  </xml>';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $final_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_slide);
		$xml_response = curl_exec($ch);
		curl_close($ch);

		$xml = simplexml_load_string($xml_response);

		$returncode = $xml->returncode;
		if ($returncode == 'SUCCESS') {
			$member_id = $this->session->userdata ('member_id');
			$this->add_to_history ($coaching_id, $class_id, $member_id);
		}
		return $returncode;
	}

	public function add_to_history ($coaching_id=0, $class_id=0, $member_id=0) {
		$data['coaching_id'] = $coaching_id;
		$data['class_id'] = $class_id;
		$data['start_date'] = time ();
		$data['end_date'] = 0;
		$data['created_by'] = $member_id;
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('class_id', $class_id);
		$this->db->where ('start_date >', 0);
		$this->db->where ('end_date', 0);
		$sql = $this->db->get ('virtual_classroom_history');
		if ($sql->num_rows () == 0) {
			$this->db->insert ('virtual_classroom_history', $data);
		} else {
			$this->db->set ('end_date', time ());
			$this->db->where ('coaching_id', $coaching_id);
			$this->db->where ('class_id', $class_id);
			$this->db->update ('virtual_classroom_history');
		}
	}


	public function my_classroom ($coaching_id=0, $member_id=0, $category_id='-1') {
		$result = [];
		$this->db->select ('VC.*, VCP.meeting_url, VCP.role');
		$this->db->from ('virtual_classroom VC');
		$this->db->join ('virtual_classroom_participants VCP', 'VC.class_id=VCP.class_id');
		$this->db->where ('VCP.coaching_id', $coaching_id);
		$this->db->where ('VCP.member_id', $member_id);
		if ($category_id > '-1') {
			$this->db->where ('VC.category_id', $category_id);
		}
		$sql = $this->db->get ();
		$sql_array = $sql->result_array ();
		if (! empty ($sql_array)) {
			foreach ($sql_array as $row) {
				$row['running'] = $this->is_meeting_running ($coaching_id, $row['class_id']);
				$result[] = $row;
			}			
		}
		return $result;
	}

	public function get_running_meetings ($coaching_id=0) {
		$meeting = [];
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('virtual_classroom');
		if ($sql->num_rows () > 0) {
			foreach ($sql->result_array () as $row) {
				$meeting[$row['class_id']] = $this->is_meeting_running ($coaching_id, $row['class_id']);
			}
		}
		return $meeting;
	}

}