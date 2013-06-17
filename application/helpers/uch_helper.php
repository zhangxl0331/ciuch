<?php
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;
	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

function formhash($uid=0, $key='', $hash='') 
{
	return substr(md5(substr(time(), 0, -7).'|'.$uid.'|'.md5($key).'|'.$hash), 8, 8);
}

function submitcheck($var, $value='') 
{
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == $value) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	} else 
	{
		return false;
	}
}

function showmessage($msgkey, $url_forward='', $second=1, $values=array())
{
	$this->load->vars(array('ad'=>''));

	if(isset($_GET['inajax']) && $url_forward && empty($second)) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url_forward");
	} else {
		$this->load->language('message');
		$message = $this->lang->line($msgkey);
		if($message) {
			if($values) {
				foreach ($values as $k => $v) {
					$rk = $k + 1;
					$message = str_replace('\\'.$rk, $v, $message);
				}
			}
		} else {
			$message = $msgkey;
		}

		if(isset($_GET['inajax'])) {
			if($url_forward) {
				$message = "<a href=\"$url_forward\">$message</a><ajaxok>";
			}
			if(isset($_GET['popupmenu_box'])) {
				$message = "<h1>&nbsp;</h1><a href=\"javascript:;\" onclick=\"hideMenu();\" class=\"float_del\">X</a><div class=\"popupmenu_inner\">$message</div>";
			}
			ob_end_clean();
			if (function_exists('ob_gzhandler')) {
				ob_start('ob_gzhandler');
			} else {
				ob_start();
			}
			@header("Expires: -1");
			@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
			@header("Pragma: no-cache");
			@header("Content-type: application/xml; charset=UC_CHARSET");
			echo '<'."?xml version=\"1.0\" encoding=\"UC_CHARSET\"?>\n";
			echo "<root><![CDATA[".trim($message)."]]></root>";

		} else {
			if($url_forward) {
				$message = "<a href=\"$url_forward\">$message</a><script>setTimeout(\"window.location.href ='$url_forward';\", ".($second*1000).");</script>";
			}
			include(APPPATH.'errors/error_message.php');
			$message = ob_get_contents();
			ob_end_clean();
			if (function_exists('ob_gzhandler')) {
				ob_start('ob_gzhandler');
			} else {
				ob_start();
			}

// 			$template = &load_class('Template', 'libraries', '');
			// 				$header = $template->build('header', array(), TRUE);
			// 				$footer = $template->build('footer', array(), TRUE);
			// 				$message = $header.$message.$footer;
			echo $message;
		}
	}
	exit();
}