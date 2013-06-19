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
class Space_l
{	
	protected $CI;
	
	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model(array(
				'space/space_m',
				'space/spacefield_m',
				'space/spacelog_m',
			)
		);
		
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
	
	public function space($value='', $key='uid')
	{		
		if( ! $value)
		{			
			@list($password, $uid) = explode("\t", authcode(get_cookie('auth'), 'DECODE', UC_KEY));
			if($value = $this->CI->input->get('uid'))
			{
				$key = 'uid';
			}
			elseif($value = $this->CI->input->get('username'))
			{
				$key = 'username';
			}
			elseif($value = $this->CI->input->get('domain'))
			{
				$key = 'domain';
			}
			elseif($value = $uid)
			{
				$key = 'uid';
			}
		}

		if($value)
		{
			$cachekey = "space_{$key}_{$value}";
			if($space = $this->CI->cache->get($cachekey))
			{
				return $space;			
			}
			
			if($space = $this->CI->space_m->get_row_array_by($key, $value))
			{
				$spacefiled = $this->CI->spacefield_m->get_row_array($space['uid']);
				$space = array_merge($space, $spacefiled);
				$space['realname'] = ($this->CI->config_l->realname && $space['name'] && $space['namestatus'])?$space['name']:$space['username'];
				$space['self'] = $space['uid'] == $this->CI->member_l->uid;
				
				$this->CI->cache->save($cachekey, $space);
				
				return $space;
			}		
		}
				
		return FALSE;
	}
	
	function space_open($uid, $username, $gid=0, $email='') 
	{
		$this->CI->load->model('space/spacelog_m');
		$this->CI->load->model('space/space_m');
		$this->CI->load->model('space/spacefield_m');
		$this->CI->load->helper('date');
		
		if($this->CI->spacelog_m->get_row_by(array('uid'=>$uid, 'flag'=>'-1')))
		{
			show_error('the_space_has_been_closed');
		}
		
		$space = array(
				'uid' => $uid,
				'username' => $username,
				'dateline' => now(),
				'groupid' => $gid
		);
		$this->CI->spacelog_m->replace_row($space);
		$this->CI->spacefield_m->replace_row(array('uid'=>$uid, 'email'=>$email));
	
		return $space;
	}	
	
	public function space_log($uid)
	{
		if($this->CI->spacelog_m->get_row_by(array('uid'=>$uid, 'flag'=>'-1')))
		{
			return TRUE;
		}
		return FALSE;
	}
}

/* End of file Settings.php */