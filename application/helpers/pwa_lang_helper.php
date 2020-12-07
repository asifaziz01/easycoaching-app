<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function _AT_TEXT ($code='', $type='', $lang='en') {
	
	$data = array ();
	
	$data['en'] = array ('msg'=>array(
			'INVALID_CREDENTIALS'=>'The username/password combination is incorrect',
			'INVALID_USERNAME'=>'We don\'t recongnise you. Please check your username',
			'INVALID_PASSWORD'=>'You have entered a wrong password',
			'MAX_ATTEMPTS_REACHED'=>'Too many retry attempts. Try again after '.(LOCK_TIME/60).' minutes',
			'LOGIN_ERROR'=>'There was some error. Please retry again',
			'ACCOUNT_DISABLED'=>'Your account is disabled or pending for admin approval. Try again later',
			'LOGIN_SUCCESSFUL'=>'Login successful. Loading your dashboard...',
			'VALIDATION_ERROR'=>validation_errors (),
			),
		);
		
	return $data[$lang][$type][$code];
}