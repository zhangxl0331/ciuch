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
class Usergroup_l
{
	protected $CI;
	
	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model('usergroup/usergroup_m');
	}

	/**
	 * Getter
	 *
	 * Gets the setting value requested
	 *
	 * @param	string	$name
	 */
	public function __get($var)
	{
		if (is_callable(array($this, get_class($this))))
		{
			return call_user_func_array(array($this, get_class($this)), (array)$var);
		}
		
		return FALSE;
	}
	
	private function _usergroup()
	{
		$cachekey = 'usergroup';
		if($usergroup = $this->CI->cache->get($cachekey))
		{
			return $usergroup;
		}
		$usergroup = array();
		$this->CI->load->model('usergroup/usergroup_m');
		if($list = $this->CI->usergroup_m->get_all_array())
		{
			$highest = true;
			$lower = '';
			foreach($list as $value)
			{
				$value['maxattachsize'] = $value['maxattachsize'] * 1024 * 1024;
				if($value['system'] == 0)
				{
					if($highest)
					{
						$value['credithigher'] = 999999999;
						$highest = false;
						$lower = $value['creditlower'];
					}
					else
					{
						$value['credithigher'] = $lower - 1;
						$lower = $value['creditlower'];
					}
				}
				$usergroup[$value['gid']] = $value;
			}
			
			$this->CI->cache->save($cachekey, $usergroup);
		}
	
		return $usergroup;
	}

	public function usergroup($var)
	{
		$usergroup = $this->_usergroup();
		$this->CI->load->library('member/member_l');
		$groupid = $this->CI->member_l->groupid;
		if($groupid AND isset($usergroup[$groupid][$var]))
		{
			return $usergroup[$groupid][$var];
		}
	
		return FALSE;
	}
	
	
	
	public function getgroupid($credit, $gid=0) 
	{
		$usergroup = $this->_usergroup();
	
		$needfind = false;
		if($gid && !empty($usergroup[$gid])) 
		{
			$group = $usergroup[$gid];
			if(empty($group['system'])) 
			{
				if($group['credithigher']<$credit || $group['creditlower']>$credit) 
				{
					$needfind = true;
				}
			}
		} 
		else 
		{
			$needfind = true;
		}
		if($needfind) 
		{
			$gid = $this->CI->usergroup_m->where(array('creditlower<='=>$credit, 'system'=>0))->get('usergroup')->order_by('creditlower', 'DESC')->limit(1)->row(0);
		}
		return $gid;
	}

}

/* End of file Settings.php */