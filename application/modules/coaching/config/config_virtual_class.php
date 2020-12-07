<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['vc'] = '';

define ('VM_PARTICIPANT_ATTENDEE', 				1);
define ('VM_PARTICIPANT_MODERATOR', 			2);

define ('VC_LOGOUT_URL', 					site_url('coaching/virtual_class/end_meeting'));
define ('VC_BANNER_TEXT', 					'WERT');
define ('VC_BANNER_COLOR', 					'#f5f5f5');
define ('VC_LOGO', 							'');
define ('VC_MAX_PARTICIPANTS', 				150);		
define ('VC_DURATION', 						300);		// in minutes
define ('VC_WELCOME_MESSAGE', 				'Live+Classroom');
define ('VC_COPYRIGHT_TEXT', 				'asas');

$config['vc_error_code'] = [
	1=>'There was an error connecting to this class. Class may not have been started yet.',
	2=>'You cannot start this class. Your role in this classroom is of Attendee. Only a participant with Moderator role can start a class. <br> To add yourself as Moderator, first remove yourself from the class and again "add as participant with moderator role".',
	3=>'You are not a participant in this classroom. To start or join this class you must be a participant with Moderator or Attendee role.',
];