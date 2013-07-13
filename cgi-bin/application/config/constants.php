<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Server specific constants
|--------------------------------------------------------------------------
|
|
*/

define('WEBSITE_URL', 'http://whoownsmyneighbourhood.org.uk');
define('LOCAL', FALSE);
define('DEBUG', FALSE);
define('COOKIE_DOMAIN', 'whoownsmyneighbourhood.org.uk');


/*
|--------------------------------------------------------------------------
| Website settings
|--------------------------------------------------------------------------
|
| Site-wide settings
|
*/

define('COOKIE_LIFE', 31536000);


/*
|--------------------------------------------------------------------------
| Website settings
|--------------------------------------------------------------------------
|
| Site-wide settings
|
*/

define('EMAIL_NEW_PASSWORD_1', "Using your new password on the Who Owns My Neighbourhood website. \nPlease enter the following code in the form provided: \n");
define('EMAIL_NEW_PASSWORD_2', "\nIf you don't want to change your password there is no need to do anything.\n");
define('EMAIL_REGISTER_1', "Welcome to the Who Owns My Neighbourhood website. \nTo complete your registration you need to enter the following code in the form provided: \n");
define('EMAIL_REGISTER_2', "\nTo cancel registration there is no need to do anything.\n");


/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/

define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);


/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./system/application/config/constants.php */