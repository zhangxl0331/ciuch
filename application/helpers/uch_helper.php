<?php
function formhash($uid=0, $key='', $hash='') {
	return substr(md5(substr(time(), 0, -7).'|'.$uid.'|'.md5($key).'|'.$hash), 8, 8);
}

function submitcheck($input, $value='') {
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $input == $value) {
			return true;
		} else {
			showmessage('submit_invalid');
		}
	} else {
		return false;
	}
}