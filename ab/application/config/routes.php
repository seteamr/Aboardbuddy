<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	example.com/class/method/id/
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
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['privacy_policy'] = 'login/privacy_policy';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// api's route
$route['sign-up'] 			= 'wb/signup';
$route['student-login'] 	= 'wb/login';
$route['support'] 			= 'wb/support';
$route['countries'] 		= 'wb/countries';
$route['cities'] 			= 'wb/cities';
$route['universities'] 		= 'wb/universities';
$route['profile'] 			= 'wb/profile';
$route['profile_update'] 	= 'wb/profile_update';
$route['find_buddies'] 		= 'wb/find_buddies';
$route['post_trip'] 		= 'wb/post_trip';
$route['message_request'] 	= 'wb/message_request';
$route['message_request_list'] = 'wb/message_request_list';
$route['my_request_list'] 	= 'wb/my_request_list';
$route['approve_reject'] 	= 'wb/approve_reject';
$route['send_message'] 		= 'wb/send_message';
$route['message_list'] 		= 'wb/message_list';
$route['messages_details'] 	= 'wb/messages_details';
$route['my_post_trip'] 		= 'wb/my_post_trip';
$route['post_trips'] 		= 'wb/post_trips';
$route['seller_registration'] = 'wb/seller_registration';
$route['seller_update'] = 'wb/seller_update';
$route['post_add'] 			= 'wb/post_add';
$route['my_post'] 			= 'wb/my_post';
$route['post_details'] 		= 'wb/post_details';
$route['post_by_city'] 		= 'wb/post_by_city';
$route['contact_us'] 		= 'wb/contact_us';

$route['home'] 				= 'wb/home'; 
$route['add_spam'] 			= 'wb/add_spam';
$route['add_carpool'] 		= 'wb/add_carpool';
$route['carpools'] 			= 'wb/carpools';
$route['join_carpool'] 		= 'wb/join_carpool';
$route['carpool_detail'] 	= 'wb/carpool_detail';
$route['carpool_delete'] 	= 'wb/carpool_delete';
$route['test'] 				= 'wb/test';