<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "home";
$route['scaffolding_trigger'] = "";

$route['captcha.png'] = "captcha/index";
$route['landreg/unknown/([0-9]+)'] = "plot/index/$1";
$route['landreg/([A-Za-z0-9]+)'] = "landreg/index/$1";
$route['landreg/([A-Za-z0-9]+)/([0-9]+)'] = "landreg/index/$1/$2";
$route['newsletter/([a-z0-9]+)/([a-z0-9]+)'] = "newsletter/index/$1/$2";
$route['plot/([A-Za-z0-9]+)'] = "plot/index/$1";
$route['region/([a-z0-9]+)'] = "region/index/$1";

$route['password'] = "password/index";
$route['place'] = "place/index";
$route['place/([0-9]+)'] = "place/index/$1";
$route['register'] = "register/index/$1";
$route['rss/([a-z0-9-]+)'] = "rss/index/$1";
$route['signin'] = "signin/index/$1";
$route['signout'] = "signout/index";

$route['test'] = "test/index";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */