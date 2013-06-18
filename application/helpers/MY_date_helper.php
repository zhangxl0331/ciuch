<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function sgmdate($dateformat, $timestamp='', $format=0, $timeoffset=0) {
	if(empty($timestamp)) {
		$timestamp = time() + $timeoffset * 3600;
	}
	$result = '';
	if($format) {
		$time = time() + $timeoffset * 3600 - $timestamp;
		if($time > 24*3600) {
			$result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
		} elseif ($time > 3600) {
			$result = intval($time/3600).lang('hour').lang('before');
		} elseif ($time > 60) {
			$result = intval($time/60).lang('minute').lang('before');
		} elseif ($time > 0) {
			$result = $time.lang('second').lang('before');
		} else {
			$result = lang('now');
		}
	} else {
		$result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
	}
	return $result;
}
// end function_blog.php

/* End of file captcha_helper.php */
/* Location: ./system/heleprs/captcha_helper.php */