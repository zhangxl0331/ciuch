<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_controller'][] = array(
	'class'    => 'Myhook',
	'function' => 'checkcache',
	'filename' => 'Myhook.php',
	'filepath' => 'hooks'
);

$hook['pre_controller'][] = array(
	'class'    => 'Myhook',
	'function' => 'checkauth',
	'filename' => 'Myhook.php',
	'filepath' => 'hooks'
);

$hook['pre_controller'][] = array(
		'class'    => 'Myhook',
		'function' => 'checkclose',
		'filename' => 'Myhook.php',
		'filepath' => 'hooks'
);

$hook['pre_controller'][] = array(
		'class'    => 'Myhook',
		'function' => 'getmember',
		'filename' => 'Myhook.php',
		'filepath' => 'hooks'
);

// $hook['post_controller_constructor'][] = array(
// 		'class'    => 'Myhook',
// 		'function' => 'checkauth',
// 		'filename' => 'Myhook.php',
// 		'filepath' => 'hooks'
// );

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */