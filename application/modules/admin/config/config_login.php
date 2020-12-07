<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['login'] = '';

define ('INCLUDE_PATH', 			    'public/layout/');

// Maximum wrong password attempts allowed (default 5)
define('MAX_WRONG_PASSWORD_ATTEMPTS',   	5);


// User will be locked out for a period of 300 (default) seconds
define('LOCK_TIME',                       	300);

// Login token expiry time (in days - default 30)
define('TOKEN_EXPIRES_IN',                  30);

// Login response statuses (Don't change)
define ('LOGIN_SUCCESSFUL', 		1);
define ('INVALID_CREDENTIALS', 		2);
define ('INVALID_USERNAME', 		3);
define ('INVALID_PASSWORD', 		4);
define ('MAX_ATTEMPTS_REACHED', 	5);
define ('LOGIN_ERROR', 				6);
define ('ACCOUNT_DISABLED', 		7);
