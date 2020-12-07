<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['vc'] = '';

define ('VM_PARTICIPANT_ATTENDEE', 				1);
define ('VM_PARTICIPANT_MODERATOR', 			2);

define ('VC_LOGOUT_URL', 					site_url('student/virtual_class/index'));
define ('VC_BANNER_TEXT', 					'Easy Coaching App');
define ('VC_BANNER_COLOR', 					'#f5f5f5');
define ('VC_LOGO', 							'');
define ('VC_MAX_PARTICIPANTS', 				100);		
define ('VC_DURATION', 						300);		// in minutes
define ('VC_RECORD', 						true);		
define ('VC_WELCOME_MESSAGE', 				'Welcome to ');