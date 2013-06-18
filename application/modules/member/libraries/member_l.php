<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * PyroCMS Settings Library
 *
 * Allows for an easy interface for site settings
 *
 * @author		Dan Horrigan <dan@dhorrigan.com>
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Settings\Libraries
 */
class Member_l
{

	protected $CI;
	
	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->library('uc/user_l', '', 'uc_user_l');
	}
	
	public function __get($var)
	{
		$method = strtolower(preg_replace('/(_l|_lib)?$/', '', get_class($this)));
	
		if (is_callable(array($this, $method)))
		{
			$callback = call_user_func(array($this, $method));
			if(isset($callback[$var]))
			{
				return $callback[$var];
			}
		}
	
		return FALSE;
	}
	
	public function member($uid=0)
	{
		$this->CI->load->model(array(
				'member/member_m',
				'member/session_m',		
		));
		$this->CI->load->helper(array('cookie', 'date', 'global'));
		
		if( $uid = intval($uid)) 
		{
			return $this->CI->member_m->get_row_array($uid);
		}
		else 
		{
			if($auth = get_cookie('auth'))
			{
				@list($password, $uid) = explode("\t", authcode($auth, 'DECODE'));
				if($uid = intval($uid)) 
				{
					if( ! $member = $this->CI->session_m->get_row_array_by(array('uid'=>$uid, 'password'=>$password)))
					{
						if( ! $member = $this->CI->member_m->get_row_array_by(array('uid'=>$uid, 'password'=>$password)))
						{
							set_cookie('auth', '', -86400 * 365);
						}
					}
					
					if($member)
					{
						$data['uid'] = $member['uid'];
						$data['username'] = $member['username'];
						$data['password'] = $member['password'];
						$data['lastactivity'] = now();
						$data['ip'] = $this->CI->input->ip_address();
						$this->CI->session_m->replace_row($data);
						
						return $member;
					}					
				}
			}
		}
		
		return FALSE;
	}
	
	
	public function replace_member($uid, $username, $password)
	{
		$this->CI->load->model('member/member_m');
	
		$this->CI->member_m->insert_row(array('uid'=>$uid, 'username'=>$username, 'password'=>$password));
	}
	
	
	public function avatar($uid, $size='middle', $type='')
	{
		$cachekey = "avatarfile_{$uid}_{$size}_{$type}";	
		if( ! $avatar = $this->CI->cache->get($cachekey))
		{
			$avatar = $this->CI->uc_user_l->avatar($uid, $size, $type);
			$this->CI->cache->save($cachekey, $avatar);
		}
		
		return $this->CI->config_l->uc_dir.$avatar;
	}
	
	public function cklogin()
	{
		if( ! $this->uid)
		{
			set_cookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
			showmessage('to_login', 'do.php?ac='.$this->config_l->login_action);
		}
	}
}

/* End of file Settings.php */