<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_model extends CI_Model {

	public function send_sms_template ($contact='', $template=SMS_USER_ACOUNT_CREATED) {
		$message = urlencode($message);
		$query_string = $this->config->item ('sms_query_string');
		$query_string .= '&number='.$contact.'&message='.$message;
		$url = SMS_API_URL . '?' . $query_string;		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$xml_response = curl_exec($ch);
		curl_close($ch);
	}

	public function send_sms ($contact='', $message='') {
		$message = urlencode($message);
		$query_string = $this->config->item ('sms_query_string');
		$query_string .= '&number='.$contact.'&message='.$message;
		$url = SMS_API_URL . '?' . $query_string;		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$xml_response = curl_exec($ch);
		curl_close($ch);
	}


	public function sms_templates ($template=SMS_USER_ACOUNT_CREATED) {
		$message = '';
		$message[SMS_USER_ACOUNT_CREATED] = '';
	}
}