<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter CAPTCHA Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Create CAPTCHA
 *
 * @access	public
 * @param	array	array of data for the CAPTCHA
 * @param	string	path to create the image in
 * @param	string	URL to the CAPTCHA image folder
 * @param	string	server path to font
 * @return	string
 */
if ( ! function_exists('showmessage'))
{
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
					$url_forward = "<a href=\"$url_forward\">页面跳转中...</a>";
				} else {
					$url_forward = "<a href=\"javascript:history.go(-1);\">返回上一页</a> | <a href=\"index.php\">返回首页</a>";
				}
				$body = '
				<div class="showmessage">
					<div class="ye_r_t"><div class="ye_l_t"><div class="ye_r_b"><div class="ye_l_b">
						<caption>
							<h2>信息提示</h2>
						</caption>
						<p>'.$message.'</p>
						<p class="op">'.$url_forward.'</p>
					</div></div></div></div>
				</div>
';
				$template = $loader->load->library('Template');
				$template->_body = $body;
			}
		}
		exit();
	}
}

// ------------------------------------------------------------------------

function show_privacy()
{
	include(APPPATH.'errors/error_privacy.php');
	$message = ob_get_contents();
	ob_end_clean();
	if (function_exists('ob_gzhandler')) {
		ob_start('ob_gzhandler');
	} else {
		ob_start();
	}
	echo $message;
	exit();
}

function ckfriend($value, $module='')
{
	$CI = & get_instance();
	$CI->load->library(array('space/space_l', 'member/member_l'));
	$CI->load->helper(array('cookie'));
	$result = false;
	if($value['friend'] == 1)
	{
		$result = $CI->space->self?true:$CI->space->isfriend;
	}
	elseif($value['friend'] == 2)
	{
		if(isset($value['target_ids']))
		{
			$target_ids = explode(',', $value['target_ids']);
			if( in_array($CI->member_l->uid, $target_ids))
			{
				$result = true;
			}
		}
	}
	elseif($value['friend'] == 3)
	{
		$result = $CI->space->self;
	}
	else
	{
		$result = 1;
	}
	
	return $result;

}

function ckinputpwd($value, $module='')
{
	$CI = & get_instance();
	if( ! $CI->space_l->self)
	{
		if( ! $module AND method_exists( $CI->router, 'fetch_module'))
		{
			$module = $CI->router->fetch_module();
		}
		$cookiename = "view_pwd_{$module}_{$value[$module.'id']}";
		$cookievalue = get_cookie($cookiename);
		return $cookievalue == md5(md5($value['password']));
	}
	
	return TRUE;
}

function mkpicurl($value, $thumb=1)
{
	$CI = & get_instance();
	$CI->load->library(array('config/config_l'));
	$url = '';
	if(isset($value['picnum']) && $value['picnum'] < 1)
	{
		$url = 'image/nopic.gif';
	}
	elseif(isset($value['picflag']))
	{
		if($value['pic'])
		{
			if($value['picflag'] == 1)
			{
				$url = $CI->config_l->attachurl.$value['pic'];
			}
			elseif($value['picflag'] == 2)
			{
				$url = $CI->config_l->ftpurl.$value['pic'];
			}
			else
			{
				$url = $value['pic'];
			}
		}
	}
	elseif(isset($value['filepath']))
	{
		$value['pic'] = $value['filepath'];
		if($value['pic'])
		{
			if($thumb && $value['thumb'])
			{
				$value['pic'] .= '.thumb.jpg';
			}
			if($value['remote'])
			{
				$url = $CI->config_l->ftpurl.$value['pic'];
			}
			else
			{
				$url = $CI->config_l->attachurl.$value['pic'];
			}
		}
	}
	else
	{
		$url = $value['pic'];
	}
	if($url && $value['friend']==4)
	{
		$url = 'image/nopublish.jpg';
	}

	return $url;
}

function formhash()
{
	$uch = &load_class('Loader', 'core')->get_var('uch');

	return substr(md5(substr($uch['timestamp'], 0, -7).'|'.$uch['supe_uid'].'|'.$uch['config']['sitekey'].'|'.$hashadd), 8, 8);
		
}

function submitcheck($var) {
	$loader = &load_class('Loader', 'core');
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == formhash()) {
			return true;
		} else {
			show_message('submit_invalid');
		}
	} else {
		return false;
	}
}

function checkperm($type)
{
	$CI = & get_instance();
	$usergroup = $CI->usergroup_l->_usergroup();
	$member = $CI->space_l->space($CI->member_l->uid);

	if($member = $CI->space_l->space($CI->member_l->uid))
	{
		$gid = $CI->usergroup_l->getgroupid($member['credit'], $member['groupid']);
		if($gid != $member['groupid'])
		{

			$CI->space_m->update_by(array('groupid'=>$gid), array('uid'=>$CI->member_l->uid));
		}
	}
	if($type == 'admin')
	{
		$type = 'manageconfig';
	}
	return empty($usergroup[$gid][$type])?'':$usergroup[$gid][$type];
}

function ckfounder($uid)
{
	$CI = & get_instance();
	$founders = empty($CI->config_l->founder)?array():explode(',', $CI->config_l->founder);
	if($uid && $founders)
	{
		return in_array($uid, $founders);
	}
	else
	{
		return false;
	}
}

function checkclose() 
{
	$CI = & get_instance();

	if($CI->config_l->close && !ckfounder($CI->member_l->uid) && !checkperm('closeignore')) 
	{
		if(empty($CI->config_l->closereason)) 
		{
			showmessage('site_temporarily_closed');
		} 
		else 
		{
			showmessage($CI->config_l->closereason);
		}
	}
	
	if((!ipaccess($CI->config_l->ipaccess) || ipbanned($CI->config_l->ipbanned)) && !ckfounder($CI->member_l->uid) && !checkperm('closeignore')) 
	{
		showmessage('ip_is_not_allowed_to_visit');
	}
}

function ipaccess($ipaccess)
{
	$CI = & get_instance();
	return empty($ipaccess)?true:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipaccess, '/')).")/", $CI->input->ip_address());
}

function ipbanned($ipbanned)
{
	$CI = & get_instance();
	return empty($ipbanned)?false:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipbanned, '/')).")/", $CI->input->ip_address());
}

function checklogin() 
{
	$CI = & get_instance();

	if(empty($CI->member_l->uid)) 
	{
		set_cookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
		showmessage('to_login', $CI->config->base_url().'ac/'.$CI->config_l->login_action);
	}
}

function partial($view, $module='', $data=array())
{
	$CI = & get_instance();
	$view = 'partials/'.$view;
	if ($module)
		{
			if (file_exists($CI->template->get_theme_path().'modules/'.$module.'/views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT)))
			{
				$path = $CI->template->get_theme_path().'modules/'.$module.'/views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT);
			}
			else
			{
				foreach (Modules::$locations as $location => $offset)
				{
					if (file_exists($location.$module.'/views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT)))
					{
						$path = $location.$module.'/views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT);
						break;
					}
				}
			}
			$string = $CI->load->_ci_load(array('_ci_path' => $path, '_ci_vars' => $data, '_ci_return' => TRUE));
			return $CI->parser->parse_string($string, $data, TRUE);
		}
		else 
		{
			if (file_exists($CI->template->get_theme_path().'views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT)))
			{
				$path = $CI->template->get_theme_path().'views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT);
			}
			else 
			{
				$path	= APPPATH.'views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT);
			}
			$string = $CI->load->_ci_load(array('_ci_path' => $path, '_ci_vars' => $data, '_ci_return' => TRUE));
			return $CI->parser->parse_string($string, $data, TRUE);
		}		
}
/* End of file captcha_helper.php */
/* Location: ./system/heleprs/captcha_helper.php */