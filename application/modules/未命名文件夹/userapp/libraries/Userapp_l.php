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
class Userapp_l {

	protected $CI;
	
	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model(array('userapp/myapp_m', 'userapp/userapp_m'));	
		$this->default_app();	
	}

	/**
	 * All
	 *
	 * Gets all the settings
	 *
	 * @return	array
	 */
	public function default_app()
	{
		$cachekey = 'userapp';
		if($userapp = $this->CI->cache->get($cachekey))
		{
			return $userapp;
		}

		if($userapp = $this->CI->myapp_m->get_all_array())
		{
			$this->CI->cache->save($cachekey, $userapp);
			
			return $userapp;
		}

		return FALSE;
	}
	
	/**
	 * Get
	 *
	 * Gets a setting.
	 *
	 * @param	string	$name
	 * @return	bool
	 */
	public function my_menu($uid, $limit=0)
	{
		$result = array();
		
		$my_menu = array();
		$my_menu_more = 0;
		$appids = array();
		if($userapp = $this->default_app())
		{
			foreach($userapp as $value)
			{
				$appids[] = $value['appid'];
			}
		}
		if($my_userapp = $this->userapp_m->where('uid', $uid)->where_not_in('appid', $appids)->get('userapp')->result())
		{
			$showcount = 0;
			foreach($my_userapp as $value)
			{
				if($value->allowsidenav)
				{
					if($limit)
					{
						if($showcount < $limit)
						{
							$my_menu[$value->appid] = $value;
							$showcount++;
						}
						else
						{
							$my_menu_more = 1;
						}
					}
					else 
					{
						$my_menu[$value->appid] = $value;
					}
				}
			}
		}
		
		$result['my_menu'] = $my_app;
		$result['my_menu_more'] = $my_menu_more;
		
		return $result;
	}
	
	public function app_code($uid, $appid=0)
	{
		return substr(md5($this->config_l->sitekey.'|'.$uid.(empty($appid)?'':'|'.$appid)), 8, 16);
	}

}

/* End of file Settings.php */