<?php
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Action extends MX_Controller {
	public function __construct() {
		$config = ['config_notification'];
		$models = ['notification_model'];
		$this->common_model->autoload_resources($config, $models);
		$this->load->helper('webpush');
	}
	public function subscribe($member_id = 0) {
		if (intval($member_id) === 0) {
			$member_id = $this->session->userdata('member_id');
		}
		$subscribeData = json_decode($this->input->raw_input_stream, true);
		if ($this->notification_model->save_subscription($member_id, $subscribeData)) {
			$this->output->set_content_type("application/json");
			$this->output->set_output(
				json_encode(
					array(
						'status' => true,
						'message' => 'Your notifications are now enabled.',
					)
				)
			);
		}
	}
	public function unsubscribe($member_id = 0) {
		if (intval($member_id) === 0) {
			$member_id = $this->session->userdata('member_id');
		}
		$subscribeData = json_decode($this->input->raw_input_stream, true);
		if ($this->notification_model->save_subscription($member_id, $subscribeData, 0)) {
			$this->output->set_content_type("application/json");
			$this->output->set_output(
				json_encode(
					array(
						'status' => true,
						'message' => 'Your notifications are now disabled.',
					)
				)
			);
		}
	}
	public function send_notification($coaching_id = 0) {
		if (intval($coaching_id) === 0) {
			$coaching_id = $this->session->userdata('coaching_id');
		}
		$webPush = new WebPush([
			'VAPID' => [
				'subject' => 'EasyCoaching App',
				'publicKey' => 'BBpQAy6d2Q1LKgwAqLU96oHM1Ugyvyq8eDiPlyptO40juyjFQV9wgC6Sem2VcjmuFKY081z30DwLYpz3kF9YA8A',
				'privateKey' => 'aX7QYnqdpMgCAUX2c4mGzRYSjNpZ2mzWiF5iQDZe-3g',
			],
		]);
		$notifications = array();
		$subscriptions = $this->notification_model->get_coaching_members_subscriptions($coaching_id, 4);
		foreach ($subscriptions as $i => $subscription) {
			extract($subscription);
			if (intval($status) === 1) {
				array_push($notifications, array(
					'subscription' => Subscription::create(array(
						"endpoint" => $endpoint,
						"expirationTime" => null,
						"keys" => array(
							"auth" => $auth,
							"p256dh" => $p256dh,
						),
					))
				));
			}
		}
		$notificationData = json_decode($this->input->raw_input_stream, true);
		extract($notificationData);
		foreach ($notifications as $notification) {
		    $webPush->sendNotification(
		        $notification['subscription'],
		        json_encode(
					array(
						"title" => $title,
						'content' => $content,
						'link' => $link
					)
				)
		    );
		}
		$notificationReport = array('success' => 0, 'failed' => 0);
		foreach ($webPush->flush() as $report) {
		    if ($report->isSuccess()) {
		        $notificationReport['success'] += 1;
		    } else {
		        $notificationReport['failed'] += 1;
		    }
		}
		$this->output->set_content_type("application/json");
		$this->output->set_output(
			json_encode(
				array(
					'status' => true,
					'total' => count($subscriptions),
					'success' => $notificationReport['success'],
					'failed' => $notificationReport['failed'],
				)
			)
		);
	}
}