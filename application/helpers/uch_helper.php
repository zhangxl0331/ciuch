<?php
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
	$loader = &load_class('Loader', 'core');
	$loader->vars(array('ad'=>''));

	if(isset($_GET['inajax']) && $url_forward && empty($second)) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url_forward");
	} else {
		$lang = &load_class('Lang', 'core');
		$lang->load('message');
		$message = $lang->line($msgkey);
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